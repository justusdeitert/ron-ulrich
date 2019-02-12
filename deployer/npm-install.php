<?php

// namespace Deployer;
//
// set('bin/npm', function () {
//     // return run('which npm');
//     return '/opt/plesk/node/9/bin/npm';
// });
//
// desc('Install npm packages');
// task('npm:install', function () {
//
//     // ----------------------------------->
//     // Installing Node Modules
//     // ----------------------------------->
//     writeln('Installing node_modules in Themes: {{release_path}}/{{theme_path}}');
//     run("cd {{release_path}}/{{theme_path}} && {{bin/npm}} install");
//
//     // ----------------------------------->
//     // Run Build Production
//     // ----------------------------------->
//     writeln('run npm build production in {{release_path}}/{{theme_path}}');
//     run("cd {{release_path}}/{{theme_path}} && {{bin/npm}} run build:production");
// });

/* (c) Anton Medvedev <anton@medv.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Deployer;

set('bin/npm', function () {
    // return 'npm';
    return '/opt/plesk/node/9/bin/npm';
});

desc('Install npm packages');
task('npm:install', function () {

    if (has('previous_release')) {
        if (test('[ -d {{previous_release}}/web/app/themes/ron-ulrich/node_modules ]')) {
            writeln('Copy node_modules from old Release');
            run('cp -R {{previous_release}}/web/app/themes/ron-ulrich/node_modules {{release_path}}');
        }
    }

    writeln('Run "npm install" to update node_modules');
    run("cd {{release_path}}/web/app/themes/ron-ulrich && {{bin/npm}} install");

    writeln('Run "npm build:production"');
    run("cd {{release_path}}/web/app/themes/ron-ulrich && {{bin/npm}} run build:production");
});
