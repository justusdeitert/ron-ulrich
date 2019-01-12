<?php

namespace Deployer;

set('bin/php', '/opt/plesk/php/7.1/bin/php');
set('bin/composer', '/usr/bin/composer');
set('install_option', 'install --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader');

desc('Installing vendors');
task('composer:install', function () {

    if (!commandExist('unzip')) {
        writeln('<comment>To speed up composer installation setup "unzip" command with PHP zip extension http://php.net/manual/en/book.zip.php</comment>');
    }

    run('cd {{release_path}} && {{bin/php}} {{bin/composer}} {{install_option}}');
    writeln('Installing Composer in Release Path: {{release_path}}');

    run('cd {{release_path}}/{{themes_path}}/{{theme_name}} && {{bin/php}} {{bin/composer}} {{install_option}}');
    writeln('Installing Composer in ThemePath: {{release_path}}/{{theme_path}}');

    // run('cd {{release_path}}/{{themes_path}}/kp-business-solutions && {{bin/php}} {{bin/composer}} {{install_option}}');
    // writeln('Installing Composer in ThemePath: {{release_path}}/{{theme_path}}');

//    run('cd {{release_path}}');
//    writeln('Installing Composer in ThemePath: {{release_path}}/{{theme_path}}');
});

desc('Changing release owner');
task('change:owner', function () {
    run('echo 1Hlfsv9d0a | sudo -S chown -R justusdeitert:psaserv {{release_path}}');
});
