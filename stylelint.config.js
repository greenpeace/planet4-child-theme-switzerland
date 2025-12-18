module.exports = {
	extends: '@wordpress/stylelint-config/stylistic',
	rules: {
		'comment-empty-line-before': [
			'always',
			{ except: [ 'first-nested' ] },
		],
		'selector-id-pattern': null,
		'selector-class-pattern': null,
		'@stylistic/max-line-length': null,
		'rule-empty-line-before': [
			'always',
			{ except: [ 'first-nested' ], ignore: [ 'after-comment' ] },
		],
		'at-rule-no-unknown': [ true, { ignoreAtRules: [ 'include' ] } ],
		'no-descending-specificity': null,
	},
	ignoreFiles: [ 'style.css', 'admin/css/editor-style.css' ],
};
