<?php

/**
 * Deployer recipes to push Bedrock database from local development
 * machine to a server and vice versa.
 *
 * Will always create a DB backup on the target machine.
 *
 * Requires these Deployer variables to be set:
 *   local_path: Absolute path to website root on local host machine
 */

namespace Deployer;
// use Dotenv;

set( 'local_path', dirname(__FILE__, 2) );
// set( 'remote_root', dirname( __FILE__ ) );

// var_dump(dirname(__FILE__, 2));
// var_dump('..' . __DIR__);
// writeln('local_path: {local_path}');
// writeln('remote_root: {remote_root}');
set( 'sites', [
    'talent-x.just' => 'talent-x.justusdeitert.de'
]);

// /**
//  * Returns the local WP URL or false, if not found.
//  * @return false|string
//  */
// $getLocalEnv = function () {
//     $localEnv = new Dotenv\Dotenv( get( 'local_path' ), '../.env' );
//     $localEnv->overload();
//     $localUrl = getenv( 'WP_HOME' );
//
//     if (!$localUrl) {
//         writeln( "<error>WP_HOME variable not found in local .env file</error>" );
//         return false;
//     }
//
//     return $localUrl;
// };
//
// /**
//  * Returns the remote WP URL or false, if not found.
//  * Downloads the remote .env file to a local tmp file
//  * to extract data.
//  *
//  * @return false|string
//  */
// $getRemoteEnv = function () {
//     $tmpEnvFile = get( 'local_path' ) . '/.env';
//     download( get( 'current_path' ) . '/.env', $tmpEnvFile );
//     $remoteEnv = new Dotenv\Dotenv( get( 'local_path' ), '../.env' );
//     $remoteEnv->overload();
//     $remoteUrl = getenv( 'WP_HOME' );
//     // Cleanup tempfile
//     runLocally( "rm {$tmpEnvFile}" );
//
//     if ( ! $remoteUrl ) {
//         writeln( "<error>WP_HOME variable not found in remote .env file</error>" );
//
//         return false;
//     }
//
//     return $remoteUrl;
// };

// /**
//  * Removes the protocol and trailing slash from submitted url.
//  *
//  * @param $url
//  * @return string
//  */
// $urlToDomain = function ($url) {
//     return preg_replace('/^https?:\/\/(.+)/i', '$1', rtrim($url, "/"));
// };

desc( 'Pulls DB from server and installs it locally, after having made a backup of local DB' );
task( 'pull:db', function () {

    // Export db
    $exportFilename = '_db_export_' . date( 'Y-m-d_H-i-s' ) . '.sql';
    $exportAbsFile  = get( 'deploy_path' ) . '/' . $exportFilename;
    writeln( "<comment>Exporting server DB to {$exportAbsFile}</comment>" );
    run( "cd {{current_path}} && wp db export {$exportAbsFile}" );

    // Download db export
    $downloadedExport = get( 'local_path' ) . '/' . $exportFilename;
    writeln( "<comment>Downloading DB export to {$downloadedExport}</comment>" );
    download( $exportAbsFile, $downloadedExport );

    // Cleanup exports on server
    writeln( "<comment>Cleaning up {$exportAbsFile} on Server</comment>" );
    run( "rm {$exportAbsFile}" );

    // Create backup of local DB
    $backupFilename = '_db_backup_' . date( 'Y-m-d_H-i-s' ) . '.sql';
    $backupAbsFile  = get( 'local_path' ) . '/backups/' . $backupFilename;
    writeln( "<comment>Making backup of DB on local machine to {$backupAbsFile}</comment>" );
    runLocally( "cd {{local_path}}; wp db export {$backupAbsFile}" );

    // Empty local DB
    writeln( "<comment>Reset local DB</comment>" );
    runLocally( "cd {{local_path}}; wp db reset" );

    // Import export file
    writeln( "<comment>Importing {$downloadedExport}</comment>" );
    runLocally( "cd {{local_path}}; wp db import {$exportFilename}" );

    // // Load local .env file and get local WP URL
    // if ( ! $localUrl = $getLocalEnv() ) {
    //     return;
    // }
    //
    // // Load remote .env file and get remote WP URL
    // if ( ! $remoteUrl = $getRemoteEnv() ) {
    //     return;
    // }
    //
    // // Also get domain without protocol and trailing slash
    // $localDomain = $urlToDomain($localUrl);
    // $remoteDomain = $urlToDomain($remoteUrl);

    // Update URL in DB
    // In a multisite environment, the DOMAIN_CURRENT_SITE in the .env file uses the new remote domain.
    // In the DB however, this new remote domain doesn't exist yet before search-replace. So we have
    // to specify the old (remote) domain as --url parameter.
    writeln( "<comment>Updating the URLs in the DB</comment>" );
    // runLocally( "cd {{remote_root}}; wp search-replace '{$remoteUrl}' '{$localUrl}' --skip-themes --url='{$remoteDomain}' --network" );
    // // Also replace domain (multisite WP also uses domains without protocol in DB)
    // runLocally( "cd {{remote_root}}; wp search-replace '{$remoteDomain}' '{$localDomain}' --skip-themes --url='{$remoteDomain}' --network" );

    // TODO: Find better solution for https to http
    // ORIGINAL doesn't work
    foreach (get('sites') as $key => $value) {
        // echo "{$key} => {$value} ";
        // print_r($arr);
        runLocally( "cd {{local_path}} && wp search-replace '{$value}' '{$key}' --skip-themes --url='{$value}' --network" );
        // run( "cd {{current_path}} && wp search-replace 'lichtfee.just' 'lichtfee.justusdeitert.de' --skip-themes --url='lichtfee.just' --network" );
    }

    // Cleanup exports on local machine
    writeln( "<comment>Cleaning up {$downloadedExport} on local machine</comment>" );
    runLocally( "rm {$downloadedExport}" );

} );

desc( 'Pushes DB from local machine to server and installs it, after having made a backup of server DB' );
// ORIGINAL // task( 'push:db', function () use ( $getLocalEnv, $getRemoteEnv, $urlToDomain ) {
task( 'push:db', function () {

    // Export db on Local
    $exportFilename = '_db_export_' . date( 'Y-m-d_H-i-s' ) . '.sql';
    $exportAbsFile  = get( 'local_path' ) . '/' . $exportFilename;
    writeln( "<comment>Exporting Local DB to {$exportAbsFile}</comment>" );
    runLocally( "cd {{local_path}}; wp db export {$exportFilename}" );

    // Upload export to server
    $uploadedExport = get( 'current_path' ) . '/' . $exportFilename;
    writeln( "<comment>Uploading export to {$uploadedExport} on Server</comment>" );
    upload( $exportAbsFile, $uploadedExport );

    // Cleanup local export
    writeln( "<comment>Cleaning up {$exportAbsFile} on local machine</comment>" );
    runLocally( "rm {$exportAbsFile}" );

    // Create backup of server DB
    $backupFilename = '_db_backup_' . date( 'Y-m-d_H-i-s' ) . '.sql';
    $backupAbsFile  = get( 'deploy_path' ) . '/backups/' . $backupFilename;
    writeln( "<comment>Making backup of DB on server to {$backupAbsFile}</comment>" );
    run( "cd {{current_path}} && wp db export {$backupAbsFile}" );

    // Empty server DB
    writeln( "<comment>Reset server DB</comment>" );
    run( "cd {{current_path}} && wp db reset" );

    // Import export file
    writeln( "<comment>Importing {$uploadedExport}</comment>" );
    run( "cd {{current_path}} && wp db import {$uploadedExport}" );

    // // Load local .env file and get local WP URL
    // if (!$localUrl = $getLocalEnv()) {
    //     return;
    // }
    //
    // // Load remote .env file and get remote WP URL
    // if (!$remoteUrl = $getRemoteEnv()) {
    //     return;
    // }
    //
    // // Also get domain without protocol and trailing slash
    // $localDomain = $urlToDomain($localUrl);
    // $remoteDomain = $urlToDomain($remoteUrl);

    // Update URL in DB
    // In a multisite environment, the DOMAIN_CURRENT_SITE in the .env file uses the new remote domain.
    // In the DB however, this new remote domain doesn't exist yet before search-replace. So we have
    // to specify the old (local) domain as --url parameter.
    writeln( "<comment>Updating the URLs in the DB</comment>" );

    // var_dump('localurl: ' . $localUrl);
    // var_dump('remoteurl: ' . $remoteUrl);

    // // ORIGINAL
    // run( "cd {{current_path}} && wp search-replace \"{$localUrl}\" \"{$remoteUrl}\" --skip-themes --url='{$localDomain}' --network" );
    // // Also replace domain (multisite WP also uses domains without protocol in DB)
    // run( "cd {{current_path}} && wp search-replace \"{$localDomain}\" \"{$remoteDomain}\" --skip-themes --url='{$localDomain}' --network" );

    // TODO: Find better solution for https to http
    // ORIGINAL doesn't work
    foreach (get('sites') as $key => $value) {
        // echo "{$key} => {$value} ";
        // print_r($arr);
        run( "cd {{current_path}} && wp search-replace '{$key}' '{$value}' --skip-themes --url='{$key}' --network" );
        // run( "cd {{current_path}} && wp search-replace 'lichtfee.just' 'lichtfee.justusdeitert.de' --skip-themes --url='lichtfee.just' --network" );
    }

    // Cleanup uploaded file
    writeln( "<comment>Cleaning up {$uploadedExport} from server</comment>" );
    run( "rm {$uploadedExport}" );

} );
