<?php
/**
 * Tests for block registration, metadata, and front-end rendering.
 *
 * @package tier-list-block
 */

/**
 * The four tier list blocks register from build/ and render their saved markup.
 */
class Test_Tier_List_Blocks extends WP_UnitTestCase {

	/**
	 * Every block the plugin registers.
	 *
	 * @var string[]
	 */
	const BLOCKS = array(
		'tier-list/tier-list',
		'tier-list/tier-item',
		'tier-list/tier-label',
		'tier-list/tier-content',
	);

	/**
	 * Absolute path to the plugin root.
	 *
	 * @return string
	 */
	private function plugin_dir(): string {
		return dirname( __DIR__, 2 );
	}

	/**
	 * Decoded src/<block>/block.json.
	 *
	 * @param string $block Block folder name, e.g. tier-list.
	 * @return array
	 */
	private function source_metadata( string $block ): array {
		$file = $this->plugin_dir() . '/src/' . $block . '/block.json';
		$this->assertFileExists( $file );

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents -- Local test fixture.
		return json_decode( file_get_contents( $file ), true );
	}

	/**
	 * Post content for a one-row tier list, as the editor saves it.
	 *
	 * The tier-list and tier-item blocks carry their default style attribute,
	 * so no attributes appear in their delimiters; the label overrides it.
	 *
	 * @return string Serialized block markup.
	 */
	private function tier_list_markup(): string {
		return '<!-- wp:tier-list/tier-list -->'
			. '<div class="wp-block-tier-list-tier-list has-background" style="background-color:#000000">'
			. '<!-- wp:tier-list/tier-item -->'
			. '<div class="wp-block-tier-list-tier-item has-background" style="background-color:#1a1a18">'
			. '<!-- wp:tier-list/tier-label {"style":{"color":{"background":"#ff7f7f"}}} -->'
			. '<div class="wp-block-tier-list-tier-label has-background" style="background-color:#ff7f7f">'
			. '<!-- wp:paragraph {"align":"center"} --><p class="has-text-align-center">S_RANK</p><!-- /wp:paragraph -->'
			. '</div>'
			. '<!-- /wp:tier-list/tier-label -->'
			. '<!-- wp:tier-list/tier-content -->'
			. '<div class="wp-block-tier-list-tier-content">'
			. '<!-- wp:paragraph --><p>FIRST_ITEM</p><!-- /wp:paragraph -->'
			. '</div>'
			. '<!-- /wp:tier-list/tier-content -->'
			. '</div>'
			. '<!-- /wp:tier-list/tier-item -->'
			. '</div>'
			. '<!-- /wp:tier-list/tier-list -->';
	}

	/**
	 * All four block types are registered after init.
	 */
	public function test_all_blocks_are_registered(): void {
		$registry = WP_Block_Type_Registry::get_instance();

		foreach ( self::BLOCKS as $name ) {
			$this->assertTrue( $registry->is_registered( $name ), "Block {$name} should be registered." );
		}
	}

	/**
	 * The registered tier-list block carries the attributes and supports from block.json.
	 */
	public function test_tier_list_metadata_matches_block_json(): void {
		$json  = $this->source_metadata( 'tier-list' );
		$block = WP_Block_Type_Registry::get_instance()->get_registered( 'tier-list/tier-list' );

		$this->assertInstanceOf( WP_Block_Type::class, $block );
		$this->assertSame( $json['apiVersion'], $block->api_version );
		$this->assertSame( $json['category'], $block->category );
		$this->assertSame( $json['keywords'], $block->keywords );
		$this->assertSame( $json['supports'], $block->supports );

		foreach ( array_keys( $json['attributes'] ) as $attribute ) {
			$this->assertArrayHasKey( $attribute, $block->attributes, "Attribute {$attribute} should be registered." );
			$this->assertSame( $json['attributes'][ $attribute ], $block->attributes[ $attribute ] );
		}

		$this->assertNull( $block->render_callback, 'Tier List is a static block and should not have a render callback.' );
	}

	/**
	 * The child blocks only allow their intended parents.
	 */
	public function test_child_blocks_declare_their_parent(): void {
		$registry = WP_Block_Type_Registry::get_instance();

		$this->assertSame( array( 'tier-list/tier-list' ), $registry->get_registered( 'tier-list/tier-item' )->parent );
		$this->assertSame( array( 'tier-list/tier-item' ), $registry->get_registered( 'tier-list/tier-label' )->parent );
		$this->assertSame( array( 'tier-list/tier-item' ), $registry->get_registered( 'tier-list/tier-content' )->parent );
	}

	/**
	 * The built editor script and front-end style are registered for every block.
	 */
	public function test_block_assets_are_registered(): void {
		$registry = WP_Block_Type_Registry::get_instance();

		foreach ( self::BLOCKS as $name ) {
			$block = $registry->get_registered( $name );

			$this->assertNotEmpty( $block->editor_script_handles, "{$name} should register an editor script." );
			$this->assertNotEmpty( $block->style_handles, "{$name} should register a front-end style." );

			foreach ( $block->editor_script_handles as $handle ) {
				$this->assertTrue( wp_script_is( $handle, 'registered' ), "Script {$handle} should be registered." );
			}

			foreach ( $block->style_handles as $handle ) {
				$this->assertTrue( wp_style_is( $handle, 'registered' ), "Style {$handle} should be registered." );
			}
		}
	}

	/**
	 * Rendering a saved tier list keeps the wrapper classes and inner text.
	 */
	public function test_renders_saved_markup(): void {
		$output = do_blocks( $this->tier_list_markup() );

		$this->assertStringContainsString( 'class="wp-block-tier-list-tier-list has-background"', $output );
		$this->assertStringContainsString( 'class="wp-block-tier-list-tier-item has-background"', $output );
		$this->assertStringContainsString( 'class="wp-block-tier-list-tier-label has-background"', $output );
		$this->assertStringContainsString( 'class="wp-block-tier-list-tier-content"', $output );
		$this->assertStringContainsString( 'style="background-color:#ff7f7f"', $output );
		$this->assertStringContainsString( 'has-text-align-center', $output );
		$this->assertStringContainsString( '>S_RANK</p>', $output );
		$this->assertStringContainsString( '>FIRST_ITEM</p>', $output );
		$this->assertStringNotContainsString( '<!-- wp:', $output, 'Block delimiters should be stripped from rendered output.' );
	}

	/**
	 * Rendering through the_content keeps the tier list intact.
	 */
	public function test_renders_through_the_content_filter(): void {
		$post_id = self::factory()->post->create(
			array( 'post_content' => $this->tier_list_markup() )
		);

		$output = apply_filters( 'the_content', get_post_field( 'post_content', $post_id ) );

		$this->assertStringContainsString( 'wp-block-tier-list-tier-list', $output );
		$this->assertStringContainsString( 'S_RANK', $output );
		$this->assertStringContainsString( 'FIRST_ITEM', $output );
	}

	/**
	 * Block registration is hooked to init at the default priority.
	 */
	public function test_registration_is_hooked_to_init(): void {
		$this->assertSame( 10, has_action( 'init', 'tier_list_block_init' ) );
	}

	/**
	 * The update checker is built for the tier-list-block slug against this plugin file.
	 */
	public function test_update_checker_claims_plugin_slug(): void {
		$this->assertNotFalse( has_filter( 'puc_is_slug_in_use-tier-list-block' ) );

		// phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores -- Hook name is defined by Plugin Update Checker.
		$claimed_by = apply_filters( 'puc_is_slug_in_use-tier-list-block', false );

		$this->assertSame( $this->plugin_dir() . '/tier-list.php', $claimed_by );
	}

	/**
	 * The plugin header version matches every block.json version.
	 */
	public function test_plugin_version_matches_block_versions(): void {
		$header = get_file_data( $this->plugin_dir() . '/tier-list.php', array( 'Version' => 'Version' ) );

		$this->assertNotEmpty( $header['Version'] );

		foreach ( array( 'tier-list', 'tier-item', 'tier-label', 'tier-content' ) as $block ) {
			$this->assertSame( $header['Version'], $this->source_metadata( $block )['version'], "{$block} block.json version should match the plugin header." );
		}
	}
}
