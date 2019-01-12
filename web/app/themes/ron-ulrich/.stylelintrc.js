// stylelint Configuration File
module.exports = {
    'extends': 'stylelint-config-standard',
    'defaultSeverity': 'warning',
    'rules': {
        "indentation": [4, {
            "severity": "warning"
        }],
        "color-hex-case": ['upper', {
            "severity": "warning"
        }],
        "no-descending-specificity": null,
        'no-empty-source': null,
        'at-rule-no-unknown': [true, {
            'ignoreAtRules': [
                'extend',
                'at-root',
                'debug',
                'warn',
                'error',
                'if',
                'else',
                'for',
                'each',
                'while',
                'mixin',
                'include',
                'content',
                'return',
                'function'
            ]
        }
        ]
    }
};
