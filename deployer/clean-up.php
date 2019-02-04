<?php

namespace Deployer;

// set('bin/npm', function () {
//     return run('which npm');
// });

desc('Clean Up Files & Folders');
task('clean_up:node_modules', function () {

    // ----------------------------------->
    // Installing Themes Node Modules
    // ----------------------------------->
    writeln('Deleting /node_modules ...');
    run("cd {{release_path}}/{{themes_path}}/{{theme_name}} && rm -R node_modules");

    // ----------------------------------->
    // Theme - Lichtfee
    // ----------------------------------->
    // writeln('run npm build production in {{release_path}}/{{themes_path}}/{{theme_name}}');
    // run("cd {{release_path}}/{{themes_path}}/{{theme_name}} && {{bin/npm}} run build:production");

    // ----------------------------------->
    // Theme - KP-Business-Solutions
    // ----------------------------------->
    // run("cd {{release_path}}/{{themes_path}}/kp-business-solutions && {{bin/npm}} run build:production");
    // writeln('run npm build production in {{release_path}}/{{themes_path}}/kp-business-solutions');
});

