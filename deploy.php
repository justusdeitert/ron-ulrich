<?php

namespace Deployer;

require 'recipe/common.php';
require 'deploy/npm-install.php';
require 'deploy/composer-install.php';
require 'deploy/update-db.php';
require 'deploy/sync-uploads.php';
// Require Slack Recipes for Posting Slack Messages

// --------------------------------->
// require 'vendor/deployer/recipes/recipe/slack.php';


set('repository', 'git@gitlab.justusdeitert.de:JD/talentX.git');

// Number of releases to keep. -1 for unlimited releases. Default to 5.
set('keep_releases', 3);

// Configure Theme Path
set( 'theme_name', 'talent-x');
set( 'themes_path', 'web/app/themes' );
set( 'theme_path', 'web/app/themes/talent-x' );

// List of shared files
set('shared_files', [
    '.env',
    'web/.htaccess'
]);

// List of shared dirs
set('shared_dirs', [
    'web/app/uploads'
]);

//set('writable_dirs', [
//    'web/app/uploads'
//]);

set( 'default_stage', 'staging' );

host('justusdeitert.de')
    ->user('justusdeitert')
    ->stage('staging')
    ->set('deploy_path', '/var/www/vhosts/justusdeitert.de/talent-x');

// Set Deployer Slack Messages
// ------------------------>
// set('user', 'JD'); // TODO: set username from git: https://deployer.org/docs/configuration
// set('slack_webhook', 'https://hooks.slack.com/services/REDACTED');
// set('slack_title', ''); // Dont need to show title in this channel
// set('slack_text', '_{{user}}_ deploying `{{branch}}` to *{{target}}*');
// set('slack_success_text', 'Deploy to *{{target}}* successful');
// set('slack_failure_text', 'Deploy to *{{target}}* failed');
// ------------------------>

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
    'change:owner',
    'deploy:writable',
    'deploy:symlink',
    'deploy:unlock',
    'cleanup', // Cleaning up old releases
    'success'
]);

before('deploy', 'slack:notify');
after('success', 'slack:notify:success');
after('deploy:failed', 'deploy:unlock' );
after('deploy:failed', 'slack:notify:failure');
