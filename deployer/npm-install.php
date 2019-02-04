<?php

namespace Deployer;

set('bin/npm', function () {
    // return run('which npm');
    return '/opt/plesk/node/9/bin/npm';
});

desc('Install npm packages');
task('npm:install', function () {

    // ----------------------------------->
    // Installing Node Modules
    // ----------------------------------->
    writeln('Installing node_modules in Themes: {{release_path}}/{{theme_path}}');
    run("cd {{release_path}}/{{theme_path}} && {{bin/npm}} install");

    // ----------------------------------->
    // Run Build Production
    // ----------------------------------->
    writeln('run npm build production in {{release_path}}/{{theme_path}}');
    run("cd {{release_path}}/{{theme_path}} && {{bin/npm}} run build:production");
});

