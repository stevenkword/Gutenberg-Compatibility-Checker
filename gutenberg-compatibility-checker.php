<?php
/**
 * Plugin Name:     Gutenberg Compatibility Checker
 * Plugin URI:      https://github.com/stevenkword/Gutenberg-Compatibility-Checker
 * Description:     Check your WordPress Plugins for compatibility with Gutenberg.
 * Author:          Steven Word
 * Author URI:      https://stevenword.com
 * Text Domain:     gutenberg-compatibility-checker
 * Domain Path:     /languages
 * Version:         1.0.0
 *
 * @package         Gutenberg_Compatibility_Checker
 */

// Exit if this file is directly accessed.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * This handles hooking into WordPress.
 *
 * @since 1.0.0
 */
class WPEngine_GutenbergCompat {

	/**
	 * Contains singleton instance.
	 *
	 * @since 1.0.0
	 * @static
	 * @var WPEngine_GutenbergCompat|null
	 */
	private static $instance = null;

	/**
	 * Settings page hook.
	 *
	 * @since 1.0.0
	 * @var string
	 */
	private $page;

	/**
	 * Returns an instance of this class.
	 *
	 * @since 1.0.0
	 * @static
	 *
	 * @return WPEngine_GutenbergCompat An instance of this class.
	 */
	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
			self::$instance->init();
		}

		return self::$instance;
	}

	/**
	 * Initializes hooks and setup environment variables.
	 *
	 * @since 0.1.0
	 * @static
	 */
	public static function init() {
		$instance = self::instance();

		// Build our tools page.
		add_action( 'admin_menu', array( $instance, 'create_menu' ) );
	}

	/**
	 * Add the settings page to the wp-admin menu.
	 *
	 * @since 1.0.0
	 *
	 * @action admin_menu
	 */
	public function create_menu() {
		// Create Tools sub-menu.
		$this->page = add_submenu_page( 'tools.php', __( 'Gutenberg Compatibility', 'gutenberg-compatibility-checker' ), __( 'Gutenberg Compatibility', 'gutenberg-compatibility-checker' ), 'manage_options', 'gutenberg-compatibility-checker', array( self::instance(), 'settings_page' ) );
	}

	/**
	 * Render method for the settings page.
	 *
	 * @since 1.0.0
	 */
	public function settings_page() {
		$classic_editor_link = admin_url( 'plugin-install.php?s=classic-editor&tab=search&type=term' );
		$gutenberg_link = admin_url( 'plugin-install.php?s=gutenberg&tab=search&type=term' );
		$phpversions =  array(
			'All' => 'All',
			'Akismet' => 'akismet',
			'Jetpack' => 'jetpack',
		);
		$test_version = '5.0.0';
		?>
		<div class="wrap wpe-pcc-wrap">
			<h1><?php _e( 'Gutenberg Compatibility Checker', 'gutenberg-compatibility-checker' ); ?></h1>
			<div class="wpe-pcc-main">
				<p><?php _e( 'Check your WordPress Plugins for compatibility with Gutenberg.', 'gutenberg-compatibility-checker' ); ?></p>
				<hr>
			</div>

			<div class="wpe-pcc-scan-options">
				<h2><?php _e( 'Scan Plugins', 'gutenberg-compatibility-checker' ); ?></h2>
				<table class="form-table wpe-pcc-form-table">
					<tbody>
						<tr>
							<th scope="row"><label for="phptest_version"><?php _e( 'Active Plugins', 'gutenberg-compatibility-checker' ); ?></label></th>
							<td>
								<fieldset>
									<?php
									foreach ( $phpversions as $name => $version ) {
										printf( '<label><input type="radio" name="phptest_version" value="%s" %s /> %s</label><br>', $version, checked( $test_version, $version, false ), $name );
									}
									?>
								</fieldset>
							</td>
						</tr>
						<tr>
							<th scope="row"></th>
								<td>
									<div class="wpe-pcc-run-scan">
										<input name="run" id="runButton" type="button" value="<?php _e( 'Scan Plugins', 'gutenberg-compatibility-checker' ); ?>" class="button-secondary" />
									</div> <!-- /wpe-pcc-run-scan -->
								</td>
							</th>
						</tr>
					</tbody>
				</table>
			</div> <!-- /wpe-pcc-scan-options -->

			<div class="wpe-pcc-results" style="display:block;">
				<hr>
				<h2>Scan Results</h2>

				<p>Congratulations, your plugins <b>ARE</b> compatible with Gutenberg!</p>

				<div class="wpe-pcc-download-report" style="display:block;">
					<a href="<?php echo esc_url( $classic_editor_link ); ?>" class="button">Test the Gutenberg Now</a>
					<br/>
					<hr>
				</div> <!-- /wpe-pcc-download-report -->

				<p>Unfortunately, all of your plugins are <b>NOT</b> yet compatible with Gutenberg!</p>

				<div class="wpe-pcc-download-report" style="display:block;">
					<a href="<?php echo esc_url( $classic_editor_link ); ?>" class="button">Install the Classic Editor</a>
					<a id="downloadReport" class="button-primary" href="#"><span class="dashicons dashicons-download"></span> <?php _e( 'Download Report', 'gutenberg-compatibility-checker' ); ?></a>
					<a class="wpe-pcc-clear-results" name="run" id="cleanupButton"><?php _e( 'Clear results', 'gutenberg-compatibility-checker' ); ?></a>
					<label class="wpe-pcc-developer-mode">
						<input type="checkbox" id="developermode" name="developermode" value="yes" />
						<?php _e( 'View results as raw text', 'gutenberg-compatibility-checker' ); ?>
					</label>
				</div> <!-- /wpe-pcc-download-report -->

				<div id="wpe-pcc-standardMode"></div>

			</div> <!-- /wpe-pcc-results -->
		</div>
		<?php
	}
}

// Register the WPEngine_GutenbergCompat instance.
WPEngine_GutenbergCompat::init();
