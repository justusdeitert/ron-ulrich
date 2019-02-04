<?php

namespace Deployer;

// Set Binarys for Composer INstall

set('bin/php', function () {
    return 'php';
    // return run('which php');
});

set('bin/composer', function () {
    // return 'composer';
    return run('which composer');
});

// Important for Production
set('install_option', 'install --verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader');

// Adding tasks
desc('Installing vendors');
task('composer:install', function () {
    writeln('Installing Composer in Release Path: {{release_path}}');
    run('cd {{release_path}} && {{bin/composer}} {{install_option}}');

    writeln('Installing Composer in ThemePath: {{release_path}}/{{theme_path}}');
    run('cd {{release_path}}/{{theme_path}} && {{bin/composer}} {{install_option}}');
});
