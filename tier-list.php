<?php
/**
 * Plugin Name:       Tier List Block
 * Plugin URI:        https://wordpress.org/plugins/tier-list-block/
 * Description:       Create TierMaker-style tier lists natively in the block editor.
 * Requires at least: 6.6
 * Requires PHP:      7.4
 * Version:           1.0.0
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

/**
 * Registers all four tier list blocks.
 */
function tier_list_block_init() {
	foreach ( array( 'tier-list', 'tier-item', 'tier-label', 'tier-content' ) as $block ) {
		register_block_type( plugin_dir_path( __FILE__ ) . 'build/' . $block );
	}
}
add_action( 'init', 'tier_list_block_init' );
