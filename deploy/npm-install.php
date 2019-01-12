<?php

namespace Deployer;

set('bin/npm', function () {
    return run('which npm');
});

desc('Install npm packages');
task('npm:install', function () {

    // ----------------------------------->
    // Installing Themes Node Modules
    // ----------------------------------->
    run("cd {{release_path}}/{{themes_path}} && {{bin/npm}} install");
    writeln('Installing node_modules in Themes: {{release_path}}/{{themes_path}}');

    // ----------------------------------->
    // Theme - Lichtfee
    // ----------------------------------->
    run("cd {{release_path}}/{{themes_path}}/{{theme_name}} && {{bin/npm}} run build:production");
    writeln('run npm build production in {{release_path}}/{{themes_path}}/lichtfee');

    // ----------------------------------->
    // Theme - KP-Business-Solutions
    // ----------------------------------->
    // run("cd {{release_path}}/{{themes_path}}/kp-business-solutions && {{bin/npm}} run build:production");
    // writeln('run npm build production in {{release_path}}/{{themes_path}}/kp-business-solutions');
});

