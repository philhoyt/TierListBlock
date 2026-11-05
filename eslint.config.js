/**
 * ESLint flat config (ESLint 10, `@wordpress/scripts` 32+). Replaces the
 * legacy .eslintrc.js, which flat config no longer reads.
 */

const wpPlugin = require( '@wordpress/eslint-plugin' );

module.exports = [
	{
		ignores: [
			'.claude/**',
			'build/**',
			'lib/**',
			'vendor/**',
			'node_modules/**',
		],
	},
	{
		// ESLint flat config discovers only *.js, *.cjs, *.mjs on its own.
		// Name every other extension here or the rules below never see it.
		files: [ '**/*.jsx', '**/*.ts', '**/*.tsx' ],
	},
	...wpPlugin.configs.recommended,
	{
		rules: {
			// @wordpress/* packages are WordPress runtime externals, resolved by
			// the dependency-extraction plugin at build time, not from node_modules.
			'import/no-unresolved': [ 'error', { ignore: [ '^@wordpress/' ] } ],
		},
	},
];
