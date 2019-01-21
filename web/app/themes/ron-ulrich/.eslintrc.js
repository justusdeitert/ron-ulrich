// ESLint Configuration File
// https://eslint.org/docs/2.0.0/rules/
// ------------------------------------>
// ESLint is an open source project originally created by Nicholas C. Zakas in June 2013.
// Its goal is to provide a pluggable linting utility for JavaScript.
// ------------------------------------>
// Rules in ESLint are divided into several categories to help you better understand their value.
// All rules are disabled by default. ESLint recommends some rules to catch common problems,
// and you can use these recommended rules by including extends: "eslint:recommended" in your configuration file.
// ------------------------------------>
module.exports = {
    'root': true,
    // An extends property value "eslint:recommended" enables a subset of core rules that report common problems,
    // which have a check mark  on the rules page. The recommended subset can change only at major versions of ESLint.
    'extends': 'eslint:recommended',
    // If you are using global variables inside of a file then it’s worthwhile to define those globals
    // so that ESLint will not warn about their usage.
    'globals': {
        'wp': true
    },
    // define the environments your script is designed to run in.
    // Each environment brings with it a certain set of predefined global variables.
    'env': {
        'node': true,
        'es6': true,
        'amd': true,
        'browser': true,
        'jquery': true
    },
    // ESLint allows you to specify the JavaScript language options you want to support.
    // By default, ESLint expects ECMAScript 5 syntax.
    // You can override that setting to enable support for other ECMAScript versions as well as JSX by using parser options.
    'parserOptions': {
        'ecmaFeatures': {
            'globalReturn': true,
            'generators': false,
            'objectLiteralDuplicateProperties': false,
            'experimentalObjectRestSpread': true,
            'jsx': false
        },
        'ecmaVersion': 2017,
        'sourceType': 'module'
    },
    // ESLint supports the use of third-party plugins. Before using the plugin you have to install it using npm.
    'plugins': [
        'import'
    ],
    'settings': {
        'import/core-modules': [],
        'import/ignore': [
            'node_modules',
            '\\.(coffee|scss|css|less|hbs|svg|json)$'
        ]
    },
    // ESLint comes with a large number of rules.
    // You can modify which rules your project uses either using configuration comments or configuration files.
    // To change a rule setting, you must set the rule ID equal to one of these values:
    // ----------------------------->
    // "off" or 0 - turn the rule off
    // "warn" or 1 - turn the rule on as a warning (doesn’t affect exit code)
    // "error" or 2 - turn the rule on as an error (exit code is 1 when triggered)
    'rules': {
        // disallow the use of console
        'no-console': 'off',
        // disallow assignment operators in conditional expressions
        'no-cond-assign': ["warn", "always"],
        // enforce consistent indentation
        'indent': ['warn', 4],
        // require or disallow semicolons instead of ASI
        'semi': 'warn',
        // specify whether backticks, double or single quotes should be used
        'quotes': ['warn', 'single'],
        // disallow or enforce trailing commas
        // --------------------->
        // 'comma-dangle': ['warn', 'always'],
        // --------------------->
        // Enforce spacing before and after comma
        'comma-spacing': 'warn',
        // enforce camelcase naming convention
        'camelcase': ['warn',
            {'properties': 'always'}
        ],
        // enforce spacing before and after keywords
        'keyword-spacing': 'warn',
        // require or disallow a space immediately following the // or /* in a comment
        'spaced-comment': ['warn', 'always'],
        'padded-blocks': ['warn', 'never'],
        // Disallow Unused Variables (no-unused-vars)
        // https://eslint.org/docs/rules/no-unused-vars
        'no-unused-vars': ['warn', { 'vars': 'all' }]
    }
};
