<?php
// Deployer recipes to push Bedrock database from local development machine to a server and vice versa.
// Will create a DB backup on the target machine.
// --------------------------------->

namespace Deployer;
// use Dotenv;

set('local_path', dirname(__FILE__, 2));

desc('Pulls DB from server and installs it locally, after having made a backup of local DB');
task('pull:db', function () {

    // Export db
    $exportFilename = '_db_export_' . date('Y-m-d_H-i-s') . '.sql';
    $exportAbsFile = get('deploy_path') . '/' . $exportFilename;
    writeln("<comment>Exporting server DB to {$exportAbsFile}</comment>");
    run("cd {{current_path}} && wp db export {$exportAbsFile}");

    // Download db export
    $downloadedExport = get('local_path') . '/' . $exportFilename;
    writeln("<comment>Downloading DB export to {$downloadedExport}</comment>");
    download($exportAbsFile, $downloadedExport);

    // Cleanup exports on server
    writeln("<comment>Cleaning up {$exportAbsFile} on Server</comment>");
    run("rm {$exportAbsFile}");

    // Check if Backup Folder exists
    // Local
    // -------------->
    $backupFolder = "{{local_path}}/backups/";

    if (!testLocally("[ -d $backupFolder ]")) {
        // Create /backups dir if it does not exist.
        runLocally("mkdir -p $backupFolder");
    }

    // Create backup of local DB
    $backupFilename = '_db_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $backupAbsFile = get('local_path') . '/backups/' . $backupFilename;
    writeln("<comment>Making backup of DB on local machine to {$backupAbsFile}</comment>");
    runLocally("cd {{local_path}}; wp db export {$backupAbsFile}");

    // Empty local DB
    writeln("<comment>Reset local DB</comment>");
    runLocally("cd {{local_path}}; wp db reset");

    // Import export file
    writeln("<comment>Importing {$downloadedExport}</comment>");
    runLocally("cd {{local_path}}; wp db import {$exportFilename}");

    // Update URL in DB
    // In a multisite environment, the DOMAIN_CURRENT_SITE in the .env file uses the new remote domain.
    // In the DB however, this new remote domain doesn't exist yet before search-replace. So we have
    // to specify the old (remote) domain as --url parameter.
    writeln("<comment>Updating the URLs in the DB</comment>");

    foreach (get('sites') as $key => $value) {
        runLocally("cd {{local_path}} && wp search-replace '{$value}' '{$key}' --skip-themes --url='{$value}' --network");
    }

    // Cleanup exports on local machine
    writeln("<comment>Cleaning up {$downloadedExport} on local machine</comment>");
    runLocally("rm {$downloadedExport}");

});

desc('Pushes DB from local machine to server and installs it, after having made a backup of server DB');
task('push:db', function () {

    // Export db on Local
    $exportFilename = '_db_export_' . date('Y-m-d_H-i-s') . '.sql';
    $exportAbsFile = '{{local_path}}/' . $exportFilename;
    writeln("<comment>Exporting Local DB to {$exportAbsFile}</comment>");
    runLocally("cd {{local_path}}; wp db export {$exportFilename}");

    // Upload export to server
    $uploadedExport = '{{current_path}}/' . $exportFilename;
    writeln("<comment>Uploading export to {$uploadedExport} on Server</comment>");
    upload($exportAbsFile, $uploadedExport);

    // Cleanup local export
    writeln("<comment>Cleaning up {$exportAbsFile} on local machine</comment>");
    runLocally("rm {$exportAbsFile}");

    // Check if Backup Folder exists
    // Local
    // -------------->
    $backupFolder = "{{deploy_path}}/backups/";

    if (!test("[ -d $backupFolder ]")) {
        // Create /backups dir if it does not exist.
        run("mkdir -p $backupFolder");
    }

    // Create backup of server DB
    $backupFilename = '_db_backup_' . date('Y-m-d_H-i-s') . '.sql';
    $backupAbsFile = get('deploy_path') . '/backups/' . $backupFilename;
    writeln("<comment>Making backup of DB on server to {$backupAbsFile}</comment>");
    writeln("cd {{current_path}} && wp db export {$backupAbsFile}");
    run("cd {{current_path}} && wp db export {$backupAbsFile}");

    // Empty server DB
    writeln("<comment>Reset server DB</comment>");
    run("cd {{current_path}} && wp db reset");

    // Import export file
    writeln("<comment>Importing {$uploadedExport}</comment>");
    run("cd {{current_path}} && wp db import {$uploadedExport}");

    // Update URL in DB
    // In a multisite environment, the DOMAIN_CURRENT_SITE in the .env file uses the new remote domain.
    // In the DB however, this new remote domain doesn't exist yet before search-replace. So we have
    // to specify the old (local) domain as --url parameter.
    writeln("<comment>Updating URLs in the DB</comment>");

    foreach (get('sites') as $key => $value) {
        run("cd {{current_path}} && wp search-replace '{$key}' '{$value}' --skip-themes --url='{$key}' --network");
    }

    // Cleanup uploaded file
    writeln("<comment>Cleaning up {$uploadedExport} from server</comment>");
    run("rm {$uploadedExport}");
});
