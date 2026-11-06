<?php
/**
 * Plugin Name:       Tier List Block
 * Plugin URI:        https://github.com/philhoyt/TierListBlock
 * Description:       Create TierMaker-style tier lists natively in the block editor.
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Version:           1.0.4
 * Author:            Phil Hoyt
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       tier-list-block
 *
 * @package tier-list-block
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once plugin_dir_path( __FILE__ ) . 'lib/plugin-update-checker/plugin-update-checker.php';

use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

$tier_list_update_checker = PucFactory::buildUpdateChecker(
	'https://github.com/philhoyt/TierListBlock/',
	__FILE__,
	'tier-list-block'
);
$tier_list_vcs_api        = $tier_list_update_checker->getVcsApi();
if ( method_exists( $tier_list_vcs_api, 'enableReleaseAssets' ) ) {
	// Only a .zip asset counts, and a release without one is skipped. The
	// default PREFER_RELEASE_ASSETS falls back to GitHub's source archive,
	// which installs as <Repo>-<tag>/ and breaks the plugin directory.
	// Without the filter the first asset wins, GitHub lists assets by name,
	// and readme.txt sorts ahead of tier-list-block.zip (PCLZIP_ERR_BAD_FORMAT).
	$tier_list_vcs_api->enableReleaseAssets( '/\.zip$/', $tier_list_vcs_api::REQUIRE_RELEASE_ASSETS );
}

/**
 * Registers all four tier list blocks.
 */
function tier_list_block_init() {
	foreach ( array( 'tier-list', 'tier-item', 'tier-label', 'tier-content' ) as $block ) {
		register_block_type( plugin_dir_path( __FILE__ ) . 'build/' . $block );
	}
}
add_action( 'init', 'tier_list_block_init' );
