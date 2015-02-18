<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    CC Content Filters
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    CC Content Filters
 * @author     David Cavins
 */
class CC_Content_Filters {

	/**
	 * Plugin version, used for cache-busting of style and script file references.
	 *
	 * @since   0.1.0
	 *
	 * @var     string
	 */
	const VERSION = '1.0.0';

	/**
	 *
	 * Unique identifier for your plugin.
	 *
	 *
	 * The variable name is used as the text domain when internationalizing strings
	 * of text. Its value should match the Text Domain file header in the main
	 * plugin file.
	 *
	 * @since    0.1.0
	 *
	 * @var      string
	 */
	protected $plugin_slug = 'cc-content-filter';

	/**
	 * Instance of this class.
	 *
	 * @since    0.1.0
	 *
	 * @var      object
	 */
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization and loading public scripts
	 * and styles.
	 *
	 * @since     0.1.0
	 */
	private function __construct() {

		// Load plugin text domain
		// add_action( 'init', array( $this, 'load_plugin_textdomain' ) );

		// Activate plugin when new blog is added
		// add_action( 'wpmu_new_blog', array( $this, 'activate_new_site' ) );

		// Load public-facing style sheet and JavaScript.
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
		// add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


		//	1. Content filter
		//	Register shortcodes
			add_action( 'init', array( $this, 'register_shortcodes') );

	}

	/**
	 * Return the plugin slug.
	 *
	 * @since    0.1.0
	 *
	 * @return    Plugin slug variable.
	 */
	public function get_plugin_slug() {
		return $this->plugin_slug;
	}

	/**
	 * Return an instance of this class.
	 *
	 * @since     0.1.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	/**
	 * Fired when the plugin is activated.
	 *
	 * @since    0.1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Activate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       activated on an individual blog.
	 */
	public static function activate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide  ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_activate();
				}

				restore_current_blog();

			} else {
				self::single_activate();
			}

		} else {
			self::single_activate();
		}

	}

	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @since    0.1.0
	 *
	 * @param    boolean    $network_wide    True if WPMU superadmin uses
	 *                                       "Network Deactivate" action, false if
	 *                                       WPMU is disabled or plugin is
	 *                                       deactivated on an individual blog.
	 */
	public static function deactivate( $network_wide ) {

		if ( function_exists( 'is_multisite' ) && is_multisite() ) {

			if ( $network_wide ) {

				// Get all blog ids
				$blog_ids = self::get_blog_ids();

				foreach ( $blog_ids as $blog_id ) {

					switch_to_blog( $blog_id );
					self::single_deactivate();

				}

				restore_current_blog();

			} else {
				self::single_deactivate();
			}

		} else {
			self::single_deactivate();
		}

	}

	/**
	 * Fired when a new site is activated with a WPMU environment.
	 *
	 * @since    0.1.0
	 *
	 * @param    int    $blog_id    ID of the new blog.
	 */
	public function activate_new_site( $blog_id ) {

		if ( 1 !== did_action( 'wpmu_new_blog' ) ) {
			return;
		}

		switch_to_blog( $blog_id );
		self::single_activate();
		restore_current_blog();

	}

	/**
	 * Get all blog ids of blogs in the current network that are:
	 * - not archived
	 * - not spam
	 * - not deleted
	 *
	 * @since    0.1.0
	 *
	 * @return   array|false    The blog ids, false if no matches.
	 */
	private static function get_blog_ids() {

		global $wpdb;

		// get an array of blog ids
		$sql = "SELECT blog_id FROM $wpdb->blogs
			WHERE archived = '0' AND spam = '0'
			AND deleted = '0'";

		return $wpdb->get_col( $sql );

	}

	/**
	 * Fired for each blog when the plugin is activated.
	 *
	 * @since    0.1.0
	 */
	private static function single_activate() {
		// @TODO: Define activation functionality here
	}

	/**
	 * Fired for each blog when the plugin is deactivated.
	 *
	 * @since    0.1.0
	 */
	private static function single_deactivate() {
		// @TODO: Define deactivation functionality here
	}

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    0.1.0
	 */
	public function load_plugin_textdomain() {

		$domain = $this->plugin_slug;
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );

		load_textdomain( $domain, trailingslashit( WP_LANG_DIR ) . $domain . '/' . $domain . '-' . $locale . '.mo' );
		load_plugin_textdomain( $domain, FALSE, basename( plugin_dir_path( dirname( __FILE__ ) ) ) . '/languages/' );

	}

	/**
	 * Register and enqueue public-facing style sheet.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_styles() {
		// if ( function_exists( 'bp_is_groups_component' ) && ccgn_is_component() )
		// 	wp_enqueue_style( $this->plugin_slug . '-plugin-styles', plugins_url( 'assets/css/public.css', __FILE__ ), array(), self::VERSION );
	}

	/**
	 * Register and enqueues public-facing JavaScript files.
	 *
	 * @since    0.1.0
	 */
	public function enqueue_scripts() {
		// only fetch the js file if on the groups directory
		// bp_is_groups_directory() is available at 2.0.0.
		// if ( bp_is_groups_component() && ! bp_current_action() && ! bp_current_item() )
		// 	wp_enqueue_script( $this->plugin_slug . '-plugin-script', plugins_url( 'channel-select.js', __FILE__ ), array( 'jquery' ), self::VERSION, TRUE );
	}

	/**
	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *        Actions:    http://codex.wordpress.org/Plugin_API#Actions
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 * @since    0.1.0
	 */
	public function action_method_name() {
		// @TODO: Define your action hook callback here
	}

	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *        Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *        Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 * @since    0.1.0
	 */
	public function filter_method_name() {
		// @TODO: Define your filter hook callback here
	}

	/* 1. Shortcodes
	*****************************************************************************/
	/**
	 * Warn WP that we have some shortcodes to watch out for.
	 *
	 * @since    1.0.0
	 */
	public function register_shortcodes(){
		add_shortcode('mapwidget', array( $this, 'render_mapwidget_shortcode' ) );
		add_shortcode('dialwidget', array( $this, 'render_dialwidget_shortcode' ) );
		add_shortcode('googlecalendar', array( $this, 'render_google_calendar_shortcode' ) );
		add_shortcode( 'group_membership_request_button', array( $this, 'cc_group_membership_pane_link_shortcode' ) );
	}

	/**
	 * 1a. Create map widget script tag via shortcode, so subscribers can include map widgets.
	 *
	 * @since    1.0.0 
	 */

	public function render_mapwidget_shortcode( $atts ){
		// Short code in WP content takes the form:
		// [mapwidget args="w=200&h=200&ids=ve,graybase,water,MSA,zctas,schSec,schEL,st_hou,st_sen,us_cong,tracts,placebnd,counties,State,roads,places&vr=graybase,water,places&bbox=-15200601.079251733,1905011.4450060106,-6130889.051048269,7149203.081593988"]

		// Final script takes the form:
		// <script src='http://maps.communitycommons.org/jscripts/mapWidget.js?w=200&h=200&ids=ve,graybase,water,MSA,zctas,schSec,schEL,st_hou,st_sen,us_cong,tracts,placebnd,counties,State,roads,places&vr=graybase,water,places&bbox=-15200601.079251733,1905011.4450060106,-6130889.051048269,7149203.081593988'></script>

		$a = shortcode_atts( array(
		        'args' => null,
		    ), $atts );
		$retval = '';

		if ( ! empty( $a['args'] ) ) {
			// Remove HTML special characters, especially ampersand entities.
			$a['args'] = htmlspecialchars_decode( $a['args'] );

			$retval = '<script src="http://maps.communitycommons.org/jscripts/mapWidget.js?' . $a['args'] . '"></script>';
		}

		return $retval;
	}

	/**
	 * 1b. Create map widget script tag via shortcode, so subscribers can include map widgets.
	 *
	 * @since    1.1.0 
	 */

	public function render_dialwidget_shortcode( $atts ){
		// Short code in WP content takes the form:
		// [dialwidget geoid="05000US06097" indicator="760"]

		// Final script takes the form:
		// <script src='http://maps.communitycommons.org/jscripts/dialWidget.js?geoid={geoid}&id={id}'></script>

		$a = shortcode_atts( array(
		        'geoid' 	=> null,
		        'indicator' => null
		    ), $atts );
		$retval = '';

		if ( ! empty( $a ) ) {
			// Remove HTML special characters, especially ampersand entities.
			$a = array_map( 'htmlspecialchars_decode' , $a);

			$retval = '<script src="http://maps.communitycommons.org/jscripts/dialWidget.js?geoid=' . $a['geoid'] . '&id=' . $a['indicator'] . '"></script>';
		}

		return $retval;
	}

	public function render_google_calendar_shortcode( $atts ){
		// Short code in WP content takes the form:
		// [googlecalendar args="height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=4m1j42139um8lvhovgb1nkf0ds%40group.calendar.google.com&amp;color=%23B1440E&amp;src=cacti.phi%40gmail.com&amp;color=%232952A3&amp;src=en.usa%23holiday%40group.v.calendar.google.com&amp;color=%232F6309&amp;ctz=America%2FLos_Angeles" width="800"]

		// Final code takes the form:
		// 	<iframe src="https://www.google.com/calendar/embed?height=600&amp;wkst=1&amp;bgcolor=%23FFFFFF&amp;src=4m1j42139um8lvhovgb1nkf0ds%40group.calendar.google.com&amp;color=%23B1440E&amp;src=cacti.phi%40gmail.com&amp;color=%232952A3&amp;src=en.usa%23holiday%40group.v.calendar.google.com&amp;color=%232F6309&amp;ctz=America%2FLos_Angeles" style="border-width:0" width="800" height="600" frameborder="0" scrolling="no"></iframe>

		$a = shortcode_atts( array(
		        'args' => null,
		        'width' => 800,
		    ), $atts );
		$retval = '';

		if ( ! empty( $a['args'] ) ) {
			// Set iframe height to match src height
			parse_str($a['args'], $query_args);

			$retval = '<iframe src="https://www.google.com/calendar/embed?' . $a['args'] . '" width="' . $a['width'] . '" height="' . $query_args['height'] . '" frameborder="0" scrolling="no" style="border-width:0"></iframe>';
		}

		return $retval;
	}


	// Build a Request Membership/Join Group button.
	// Takes the form: [group_membership_request_button group_id="3"]
	// Will only create the link for logged-in users who do not belong to the group.
	function cc_group_membership_pane_link_shortcode( $attr, $content = null ) {
	  $retval = '';

	  if ( $user_id = get_current_user_id() ) { 

	    $atts = shortcode_atts( array( 'group_id' => 0, 'button' => false ), $attr );
	    // If no group id was specified, try to get the current group's id
	    $group_id = ( $atts['group_id'] ) ? $atts['group_id'] : bp_get_current_group_id();

	    // Don't show this link to group members.
	    if ( $group_id && ! groups_is_user_member( $user_id, $group_id ) ) {
	      $group = groups_get_group( array( 'group_id' => $group_id ) );
	      $group_name = bp_get_group_name( $group );
	      // This comes from bp_groups_template.php
	      // It's included here rather than just used as is ( bp_get_group_join_button( $group ) ) because we want to change the labels.
	      // Show different buttons based on group status
			switch ( $group->status ) {
				case 'hidden' :
					return false;
					break;

				case 'public':
					$button = array(
						'id'                => 'join_group',
						'component'         => 'groups',
						'must_be_logged_in' => true,
						'block_self'        => false,
						'wrapper_class'     => 'group-button ' . $group->status,
						'wrapper_id'        => 'groupbutton-' . $group->id,
						'link_href'         => wp_nonce_url( bp_get_group_permalink( $group ) . 'join', 'groups_join_group' ),
						'link_text'         => 'Join ' . $group_name,
						'link_title'        => 'Join ' . $group_name,
						'link_class'        => 'group-button join-group',
					);
					break;

				case 'private' :

					// Member has outstanding invitation -
					// show an "Accept Invitation" button
					if ( $group->is_invited ) {
						$button = array(
							'id'                => 'accept_invite',
							'component'         => 'groups',
							'must_be_logged_in' => true,
							'block_self'        => false,
							'wrapper_class'     => 'group-button ' . $group->status,
							'wrapper_id'        => 'groupbutton-' . $group->id,
							'link_href'         => add_query_arg( 'redirect_to', bp_get_group_permalink( $group ), bp_get_group_accept_invite_link( $group ) ),
							'link_text'         => 'Accept Invitation to ' . $group_name,
							'link_title'        => 'Accept Invitation to ' . $group_name,
							'link_class'        => 'group-button accept-invite',
						);

					// Member has requested membership but request is pending -
					// show a "Request Sent" button
					} elseif ( $group->is_pending ) {
						$button = array(
							'id'                => 'membership_requested',
							'component'         => 'groups',
							'must_be_logged_in' => true,
							'block_self'        => false,
							'wrapper_class'     => 'group-button pending ' . $group->status,
							'wrapper_id'        => 'groupbutton-' . $group->id,
							'link_href'         => bp_get_group_permalink( $group ),
							'link_text'         => 'Request Sent to ' . $group_name,
							'link_title'        => 'Request Sent to ' . $group_name,
							'link_class'        => 'group-button pending membership-requested',
						);

					// Member has not requested membership yet -
					// show a "Request Membership" button
					} else {
						$button = array(
							'id'                => 'request_membership',
							'component'         => 'groups',
							'must_be_logged_in' => true,
							'block_self'        => false,
							'wrapper_class'     => 'group-button ' . $group->status,
							'wrapper_id'        => 'groupbutton-' . $group->id,
							'link_href'         => wp_nonce_url( bp_get_group_permalink( $group ) . 'request-membership', 'groups_request_membership' ),
							'link_text'         => 'Request Membership in ' . $group_name,
							'link_title'        => 'Request Membership in ' . $group_name,
							'link_class'        => 'group-button request-membership',
						);
					}

					break;
			}

	      $retval = bp_get_button( apply_filters( 'bp_get_group_join_button', $button ) );
	    }
	  }

	  return $retval;
	}

}