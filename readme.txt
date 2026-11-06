=== Tier List Block ===
Contributors:      philhoyt
Tags:              block, tier list, tiermaker, ranking
Requires at least: 6.6
Tested up to:      7.1
Stable tag:        1.0.5
Requires PHP:      7.4
License:           GPL-2.0-or-later
License URI:       https://www.gnu.org/licenses/gpl-2.0.html

Adds a Tier List block for building TierMaker-style ranking lists in the block editor

== Description ==

Tier List Block adds a Tier List block to the block editor. A new tier list starts with five rows labelled S, A, B, C and D, each with its own label colour. You can rename a label, change its background and text colour, reorder rows with the block mover, delete rows, and add more rows from the Tiers panel in the block sidebar.

Each row has a content zone that accepts any block. Drop in images, paragraphs, buttons or anything else; items wrap in a row inside the zone. The Gallery and Group blocks are not allowed there, because the editor would otherwise merge adjacent images into them.

The tier list and each row have a background colour control, and the block supports wide and full alignment.

The front end is static HTML and CSS. No JavaScript is loaded for visitors. Rows stack the label above the content on screens narrower than 480 px.

== Installation ==

1. Download `tier-list-block.zip` from the latest release on the [GitHub Releases page](https://github.com/philhoyt/TierListBlock/releases).
2. In your WordPress admin, go to Plugins > Add New Plugin > Upload Plugin.
3. Choose the downloaded zip and click Install Now, then Activate Plugin.
4. Open a post or page in the block editor and search for "Tier List".

Updates are delivered from GitHub releases through the bundled Plugin Update Checker.

== Frequently Asked Questions ==

= Can visitors drag items between tiers? =

No. Ranking happens in the editor; the published page is a static list.

= Why can't I add a Gallery or Group block to a tier? =

Those two blocks are blocked in the content zone. Without that, the editor turns images dropped next to each other into a Gallery, which breaks the row layout. Add images one at a time instead.

== Changelog ==

= 1.0.5 =
* Fixed dashboard updates installing the release's readme.txt instead of the plugin zip (PCLZIP_ERR_BAD_FORMAT). Sites on 1.0.4 or earlier: upload this release's zip once; later updates arrive normally.
* Pointed the Plugin URI at the GitHub repository and rewrote the readme files in plain English.

= 1.0.4 =
* Tested up to WordPress 7.1.
* Upgraded the build tooling to @wordpress/scripts 37 and added PHPUnit tests for block registration and rendering.

= 1.0.3 =
* Fixed an error when checking for updates. The update checker's changelog parser files were missing from the 1.0.2 download.

= 1.0.2 =
* Fixed automatic updates from GitHub for sites running an earlier version. Release tags were named in a form the update checker did not recognise.

= 1.0.1 =
* Fixed the block editor turning images inside Tier Content into Gallery or Row blocks.

= 1.0.0 =
* Initial release.
