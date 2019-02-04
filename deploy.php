<?php
namespace Deployer;

require 'recipe/common.php';

// Require all files in deployer folder
// ------------------------------------------->
foreach (new \DirectoryIterator(dirname(__FILE__) . '/deployer') as $fileinfo) {
    if (!$fileinfo->isDot()) {
        require $fileinfo->getPathname();
    }
}


// Set DNS Hosts for Database update
// More Hosts For Multisite
// deployer/sync-database.php
// ----------------->
set( 'sites', [
    'ron-ulrich.just' => 'www.ron-ulrich.de'
    // 'example.main' => 'kids.chimosa.justusdeitert.de',
]);


// Uploads all files (and directories) from local machine to remote server.
// Overwrites existing files on server with updated local files and uploads new files.
// Locally deleted files are not deleted on server.
// deployer/sync-dirs.php
// ----------------->
set('sync_dirs', [
    dirname(__FILE__) . '/web/app/uploads/' => '{{deploy_path}}/shared/web/app/uploads/',
]);

// Configure Theme Path
set( 'theme_path', 'web/app/themes/ron-ulrich' );

// Project name
set('application', 'ron-ulrich');

// Project repository
set('repository', 'git@gitlab.justusdeitert.de:JD/ron-ulrich.git');

// [Optional] Allocate tty for git clone. Default value is false.
set('git_tty', true);

// Shared files/dirs between deploys
set('shared_files', [
    'bedrock/.env',
    'bedrock/web/.htaccess'
]);

set('shared_dirs', [
    'bedrock/web/app/uploads'
]);

// Writable dirs by web server
set('writable_dirs', []);
set('allow_anonymous_stats', false);

// Hosts
host('justusdeitert.root')
    ->set('deploy_path', '/var/www/vhosts/ron-ulrich.de')
    ->set('branch', 'development');

// Tasks
// https://deployer.org/docs/advanced/deploy-strategies.html
desc('Deploy your project');
task('deploy', [
    'deploy:info',
    'deploy:prepare',
    'deploy:lock',
    'deploy:release',
    'deploy:update_code',
    'deploy:shared',
    'composer:install',
    'npm:install',
    'deploy:writable',
    'deploy:vendors',
    'deploy:clear_paths',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup',
    'success'
]);

desc('Push Project DB & Uploads Folder');
task('push', [
    'push:db',
    'push:files'
]);

desc('Pull Project DB & Uploads Folder');
task('pull', [
    'pull:db',
    'pull:files'
]);

// [Optional] If deploy fails automatically unlock.
after('deploy:failed', 'deploy:unlock');

// Set Deployer Recipe Slack Messages
// ------------------------>
// https://deployer.org/recipes/slack.html
// set('user', 'JD'); // TODO: set username from git: https://deployer.org/doc/*s/configuration
// set('slack_webhook', 'https://hooks.slackdep.com/services/T6MCX1UKU/BFLSA8NFM/BYq4fE0XDcTYjrrMrMFgc6S0');
// set('slack_title', ''); // We don't need to show title in this channel
// set('slack_text', '_{{user}}_ deploying `{{branch}}` to *{{target}}*');
// set('slack_success_text', 'Deploy to *{{target}}* successful');
// set('slack_failure_text', 'Deploy to *{{target}}* failed');

// Fire Slack Notifications on
// ------------->
// before('deploy', 'slack:notify');
// after('success', 'slack:notify:success');
// after('deploy:failed', 'slack:notify:failure');
