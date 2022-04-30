<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.example.com/unknown
 * @since      1.0.0
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/admin
 * @author     Developer Junayed <admin@easeare.com>
 */
class Cfa_Events_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cfa_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cfa_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/cfa-events-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Cfa_Events_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Cfa_Events_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'wp-color-picker');
		wp_enqueue_script( 'wp-color-picker');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/cfa-events-admin.js', array( 'jquery' ), $this->version, false );

	}

	function events_post_type(){
		$labels = array(
			'name'                  => __( 'Events', 'cfa-events' ),
			'singular_name'         => __( 'CFA Event', 'cfa-events' ),
			'menu_name'             => __( 'CFA Events', 'cfa-events' ),
			'name_admin_bar'        => __( 'Events', 'cfa-events' ),
			'add_new'               => __( 'New event', 'cfa-events' ),
			'add_new_item'          => __( 'New event', 'cfa-events' ),
			'new_item'              => __( 'New event', 'cfa-events' ),
			'edit_item'             => __( 'Edit event', 'cfa-events' ),
			'view_item'             => __( 'View event', 'cfa-events' ),
			'all_items'             => __( 'Events', 'cfa-events' ),
			'search_items'          => __( 'Search events', 'cfa-events' ),
			'parent_item_colon'     => __( 'Parent events:', 'cfa-events' ),
			'not_found'             => __( 'No events found.', 'cfa-events' ),
			'not_found_in_trash'    => __( 'No events found in Trash.', 'cfa-events' )
		);
		$args = array(
			'labels'             => $labels,
			'description'        => 'events custom post type.',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'events' ),
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => 45,
			'menu_icon'      	 => 'dashicons-calendar-alt',
			'supports'           => array( 'title', 'excerpt', 'editor', 'thumbnail' ),
			'show_in_rest'       => true
		);
		  
		register_post_type( 'events', $args );

		if(get_option( 'cfa_events_permalinks_flush' ) !== $this->version ){
			flush_rewrite_rules(false);
			update_option( 'cfa_events_permalinks_flush', $this->version );
		}
	}

	// Add meta boxespostcustom
	function events_post_type_metaboxes(){
		add_meta_box( 'eventdate', "Event date", [$this, 'event_date_meta_box'], 'events', 'side' );
		add_meta_box( 'eventmetadata', "Event Info", [$this, 'event_metadata_meta_box'], 'events', 'side' );
		add_meta_box( 'postimagediv', "Featured image", 'post_thumbnail_meta_box', 'events', 'side' );
		add_meta_box( 'event_email', "Email template", [$this, 'post_event_email_meta_box'], 'events', 'advanced' );
		add_meta_box( 'registrants', "Registrants", [$this, 'post_registrants_meta_box'], 'events', 'advanced' );
	}

	// Event date
	function event_date_meta_box($post){
		$date = get_post_meta($post->ID, "__event_date", true);
		echo '<input type="date" class="widefat" name="event_date" id="event_date" value="'.$date.'">';
	}
	
	// Event Info
	function event_metadata_meta_box($post){
		$location = get_post_meta($post->ID, "__event_location", true);
		$location_url = get_post_meta($post->ID, "__event_location_url", true);
		$venue_info = get_post_meta($post->ID, "__event_venue_info", true);
		$start_time = get_post_meta($post->ID, "__event_start_time", true);
		$end_time = get_post_meta($post->ID, "__event_end_time", true);
		?>
		<div class="event_metadata">
			<table style="text-align: left;">
				<tr>
					<th><label for="event_start_time">Start Time</label></th>
					<td><input type="time" class="widefat" name="event_start_time" id="event_start_time" value="<?php  echo $start_time ?>"></td>
				</tr>
				<tr>
					<th><label for="event_end_time">End Time</label></th>
					<td><input type="time" class="widefat" name="event_end_time" id="event_end_time" value="<?php  echo $end_time ?>"></td>
				</tr>
				<tr>
					<th><label for="venue_info">Venue</label></th>
					<td><input type="text" class="widefat" name="venue_info" id="venue_info" value="<?php echo $venue_info ?>"></td>
				</tr>
				<tr>
					<th><label for="location_addr">Address</label></th>
					<td><input type="text" class="widefat" name="location_addr" id="location_addr" value="<?php echo $location ?>"></td>
				</tr>
				<tr>
					<th><label for="location_url">Google map URL</label></th>
					<td><input type="text" class="widefat" placeholder="Map URL" name="location_url" id="location_url" value="<?php echo $location_url ?>"></td>
				</tr>
			</table>
		</div>
		<?php
	}

	// Email template
	function post_event_email_meta_box($post){
		$eml_title = sanitize_text_field( get_post_meta($post->ID, 'cfa_email_title' , true) );
		$email_subject = sanitize_text_field( get_post_meta($post->ID, 'cfa_email_subject' , true) );
		$email_contents = get_post_meta($post->ID, 'cfa_email_contents' , true);
		$email_footer = get_post_meta($post->ID, 'cfa_email_footer' , true);
		?>
		<div class="cfa_email_setting">
			<div class="cfa_eml_input">
				<label for="">Email title</label>
				<input type="text" class="widefat" placeholder="Title" value="<?php echo $eml_title ?>" name="cfa_email_title" id="cfa_email_title">
			</div>
			<div class="cfa_eml_input">
				<label for="">Email subject</label>
				<input type="text" class="widefat" placeholder="Subject" value="<?php echo $email_subject ?>" name="cfa_email_subject" id="cfa_email_subject">
			</div>
			<div class="cfa_eml_input">
				<label for="">Email contents</label>
				<?php
				wp_editor( $email_contents, 'cfa_email_contents', [
					'media_buttons' => false,
					'editor_height' => 200,
				] );
				?>
			</div>
			<div class="cfa_eml_input">
				<label for="">Email footer</label>
				<?php
				wp_editor( $email_footer, 'cfa_email_footer', [
					'media_buttons' => false,
					'editor_height' => 100,
				] );
				?>
			</div>
		</div>
		<?php
	}

	// Registrants
	function post_registrants_meta_box($post){
		require_once plugin_dir_path( __FILE__ )."partials/registrants-table.php";
	}

	// Manage table columns
	function manage_events_columns($columns) {
		unset(
			$columns['title'],
			$columns['date']
		);
	
		$new_columns = array(
			'title' => __('Title', 'cfa-events'),
			'event_date' => __('Date', 'cfa-events'),
			'registrants' => __('Registrants', 'cfa-events'),
			'durations' => __('Duration', 'cfa-events'),
			'date' => __('Created', 'cfa-events'),
		);
	
		return array_merge($columns, $new_columns);
	}

	// Remove Quick edit
	function remove_quick_edit_events( $actions, $post ) {
		if(get_post_type( $post ) === 'events'){
			unset($actions['inline hide-if-no-js']);
			return $actions;
		}else{
			return $actions;
		}
	}

	//   Remove edit option from bulk
	function remove_events_edit_actions( $actions ){
		unset( $actions['edit'] );
		return $actions;
	}

	function manage_events_sortable_columns($columns){
		// $columns['event_date'] = 'event_date';
		// $columns['registrants'] = 'registrants';
		return $columns;
	}
	
	// View custom column data
	function manage_events_columns_views($column_id, $post_id){
		switch ($column_id) {
			case 'event_date':
				$cfa_date = get_post_meta($post_id, '__event_date', true);
				if($cfa_date){
					echo date("F j, Y", strtotime($cfa_date));
				}
				break;
			case 'registrants':
				global $wpdb;
				$registrants = $wpdb->query("SELECT ID FROM {$wpdb->prefix}cfa_registrants WHERE event_id = $post_id");
				echo (($registrants) ? $registrants : 0);
				break;
			case 'durations':
				
				$start_time = get_post_meta($post_id, '__event_start_time', true);
				$end_time = get_post_meta($post_id, '__event_end_time', true);
				if($start_time && $end_time)
					echo date("g:ia", strtotime($start_time))." - ".date("g:ia", strtotime($end_time));
				break;
		}
	}

	function events_post_row_actions($actions, $post){
		if ($post->post_type === "events"){
			$actions['export_registrants'] = '<a href="?post_type=events&cfaction=export&post='.$post->ID.'" class="export_registrants">Export</a>';
		}
		return $actions;
	}
	
	// Remove default month filter from events post table
	function __return_empty_array($months, $post_type){
		$months=[];
		return $months;
	}
	
	// Add custom meta filter in event post type
	function add_year_filter_to_admin_area($post_type){
		if('events' !== $post_type){
			return; //check to make sure this is your cpt
		}
		
		?>
		<select name="cfa_year_filter" id="cfa_year_filter">
			<option value="all">Show all years</option>
			<?php
			$args = array(
				'post_type' => 'events',
				'post_status' => 'publish',
				'numberposts' => -1
			);
			
			$events = get_posts($args);

			$yearsList = [];
			if($events){
				foreach($events as $event){
					$cfa_date = get_post_meta($event->ID, '__event_date', true);
					if($cfa_date){
						$cfa_date = date("j F, Y", strtotime($cfa_date));

						if($cfa_date){
							$y = explode(', ', $cfa_date)[1];
							$yearsList[$y] = $y;
						}
					}
				}
			}
			
			rsort($yearsList);
			if(sizeof($yearsList) > 0){
				foreach($yearsList as $year){
					$selected = ((isset($_REQUEST['cfa_year_filter'])) ? $_REQUEST['cfa_year_filter'] : '');
					echo '<option '.(($selected === $year) ? 'selected' : '').' value="'.$year.'">'.$year.'</option>';
				}
			}
			?>
		</select>
		<?php
	}

	// Filter action events post table
	function events_post_table_filter( $query ) {
		//modify the query only if it is admin and main query.
		if( !(is_admin() AND $query->is_main_query()) ){ 
			return $query;
		}
		//we want to modify the query for the targeted custom post.
		if( 'events' !== $query->query['post_type'] ){
			return $query;
		}

		if(isset($_REQUEST['cfa_year_filter']) && $_REQUEST['cfa_year_filter'] !== 'all'){
			$year = $_REQUEST['cfa_year_filter'];
			if(!empty($year)){
				$query->query_vars['meta_query'] = array(
					array(
						'key' => '__event_date',
						'value' => $year,
						'compare' => 'LIKE'
					)
				);
			}
		}else{
			$query->set('orderby', 'meta_value');
        	$query->set('meta_key', '__event_date');
			$query->set('meta_type', 'DATE');
			$query->set('order', 'DESC');
		}

		return $query;
	}

	// Save events post meta
	function save_events_meta($post_id){
		if(isset($_POST['event_date'])){
			$date = $_POST['event_date'];
			update_post_meta( $post_id, '__event_date', $date );
		}
		if(isset($_POST['event_start_time'])){
			$start_time = $_POST['event_start_time'];
			update_post_meta( $post_id, '__event_start_time', $start_time );
		}
		if(isset($_POST['event_end_time'])){
			$end_time = $_POST['event_end_time'];
			update_post_meta( $post_id, '__event_end_time', $end_time );
		}
		if(isset($_POST['location_addr'])){
			$location = sanitize_text_field( $_POST['location_addr'] );
			update_post_meta( $post_id, '__event_location', $location );
		}
		if(isset($_POST['location_url'])){
			$longitude = $_POST['location_url'];
			update_post_meta( $post_id, '__event_location_url', $longitude );
		}
		if(isset($_POST['venue_info'])){
			$venue_info = sanitize_text_field( $_POST['venue_info'] );
			update_post_meta( $post_id, '__event_venue_info', $venue_info );
		}

		if(isset($_POST['cfa_email_title'])){
			$email_title = sanitize_text_field($_POST['cfa_email_title']);
			update_post_meta( $post_id, 'cfa_email_title', $email_title );
		}
		if(isset($_POST['cfa_email_subject'])){
			$email_subject = sanitize_text_field($_POST['cfa_email_subject']);
			update_post_meta( $post_id, 'cfa_email_subject', $email_subject );
		}
		if(isset($_POST['cfa_email_contents'])){
			$email_contents = $_POST['cfa_email_contents'];
			update_post_meta( $post_id, 'cfa_email_contents', $email_contents );
		}
		if(isset($_POST['cfa_email_footer'])){
			$email_footer = $_POST['cfa_email_footer'];
			update_post_meta( $post_id, 'cfa_email_footer', $email_footer );
		}
	}

	// menupage
	function admin_menupage(){
		add_submenu_page( 'edit.php?post_type=events', 'Settings', 'Settings', 'manage_options', 'cfa-setting', [$this, 'menupage_callback'] );
		add_settings_section( 'cfa_events_general_opt_section', '', '', 'cfa_events_general_opt_page' );
		add_settings_section( 'cfa_styles_opt_section', '', '', 'cfa_styles_opt_page' );
		// Shortcodes
		add_settings_field( 'events_shortcode', 'Shortcodes', [$this, 'events_shortcode_cb'], 'cfa_events_general_opt_page','cfa_events_general_opt_section' );
		register_setting( 'cfa_events_general_opt_section', 'events_shortcode' );
		// Excerpt length
		add_settings_field( 'excerpt_length', 'Excerpt length', [$this, 'excerpt_length_cb'], 'cfa_events_general_opt_page','cfa_events_general_opt_section' );
		register_setting( 'cfa_events_general_opt_section', 'excerpt_length' );
		// Events perpage
		add_settings_field( 'events_perpage', 'Events per page', [$this, 'events_perpage_cb'], 'cfa_events_general_opt_page','cfa_events_general_opt_section' );
		register_setting( 'cfa_events_general_opt_section', 'events_perpage' );
		// Fallback event thumbnail
		add_settings_field( 'cfa_fallback_thumb', 'Fallback event thumbnail', [$this, 'cfa_fallback_thumb_cb'], 'cfa_events_general_opt_page','cfa_events_general_opt_section' );
		register_setting( 'cfa_events_general_opt_section', 'cfa_fallback_thumb' );

		// Static color
		add_settings_field( 'cfa_static_color', 'Static color', [$this, 'cfa_static_color_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_static_color' );
		// Static text color
		add_settings_field( 'cfa_static_text_color', 'Static text color', [$this, 'cfa_static_text_color_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_static_text_color' );
		// Selected color
		add_settings_field( 'cfa_selected_color', 'Selected color', [$this, 'cfa_selected_color_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_selected_color' );
		// Selected text color
		add_settings_field( 'cfa_selected_text_color', 'Selected text color', [$this, 'cfa_selected_text_color_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_selected_text_color' );
		// Title color
		add_settings_field( 'cfa_title_color', 'Title color', [$this, 'cfa_title_color_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_title_color' );
		// Title font size (Card)
		add_settings_field( 'cfa_card_title_font_size', 'Title font size (Card)', [$this, 'cfa_card_title_font_size_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_card_title_font_size' );
		// Title font weight (Card)
		add_settings_field( 'cfa_card_title_font_weight', 'Title font weight (Card)', [$this, 'cfa_card_title_font_weight_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_card_title_font_weight' );
		// Title font size (Single page)
		add_settings_field( 'cfa_single_title_font_size', 'Title font size (Single page)', [$this, 'cfa_single_title_font_size_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_single_title_font_size' );
		// Title font weight (Single page)
		add_settings_field( 'cfa_single_title_font_weight', 'Title font weight (Single page)', [$this, 'cfa_single_title_font_weight_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_single_title_font_weight' );
		// Date color
		add_settings_field( 'cfa_date_color', 'Date color', [$this, 'cfa_date_color_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_date_color' );
		// Date font size
		add_settings_field( 'cfa_date_font_size', 'Date font size', [$this, 'cfa_date_font_size_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_date_font_size' );
		// Date font weight
		add_settings_field( 'cfa_date_font_weight', 'Date font weight', [$this, 'cfa_date_font_weight_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_date_font_weight' );
		// Content text color
		add_settings_field( 'cfa_content_text_color', 'Content text color', [$this, 'cfa_content_text_color_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_content_text_color' );
		// Content font size
		add_settings_field( 'cfa_content_font_size', 'Content font size', [$this, 'cfa_content_font_size_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_content_font_size' );
		// Content font weight
		add_settings_field( 'cfa_content_font_weight', 'Content font weight', [$this, 'cfa_content_font_weight_cb'], 'cfa_styles_opt_page','cfa_styles_opt_section' );
		register_setting( 'cfa_styles_opt_section', 'cfa_content_font_weight' );
	}

	function events_shortcode_cb(){
		echo '<input type="text" style="width: 130px; text-align: center;" readonly value="[latest_events]"> <input type="text" style="width: 130px; text-align: center;" readonly value="[previous_events]"> <input type="text" style="width: 130px; text-align: center;" readonly value="[future_events]">';
	}
	function excerpt_length_cb(){
		echo '<input type="number" placeholder="10 words" style="width: 100px;" min="10" oninput="this.value = ((this.value !== \'\') ? Math.abs(this.value) : \'\')" value="'.get_option('excerpt_length').'" name="excerpt_length" id="excerpt_length">';
	}
	function events_perpage_cb(){
		echo '<input type="number" placeholder="12" style="width: 60px;" min="1" oninput="this.value = ((this.value !== \'\') ? Math.abs(this.value) : \'\')" value="'.get_option('events_perpage').'" name="events_perpage" id="events_perpage">';
	}
	function cfa_fallback_thumb_cb(){
		echo '<input type="url" class="widefat" placeholder="Image URL" value="'.get_option('cfa_fallback_thumb').'" name="cfa_fallback_thumb" id="cfa_fallback_thumb">';
	}


	function cfa_static_color_cb(){
		echo '<input type="text" name="cfa_static_color" id="cfa_static_color" data-default-color="#3E3F94" value="'.((get_option('cfa_static_color')) ? get_option('cfa_static_color') : '#3E3F94').'">';
	}
	function cfa_static_text_color_cb(){
		echo '<input type="text" name="cfa_static_text_color" id="cfa_static_text_color" data-default-color="#FFFFFF" value="'.((get_option('cfa_static_text_color')) ? get_option('cfa_static_text_color') : '#FFFFFF').'">';
	}
	function cfa_selected_color_cb(){
		echo '<input type="text" name="cfa_selected_color" id="cfa_selected_color" data-default-color="#8FD9F9" value="'.((get_option('cfa_selected_color')) ? get_option('cfa_selected_color') : '#8FD9F9').'">';
	}
	function cfa_selected_text_color_cb(){
		echo '<input type="text" name="cfa_selected_text_color" id="cfa_selected_text_color" data-default-color="#ffffff" value="'.((get_option('cfa_selected_text_color')) ? get_option('cfa_selected_text_color') : '#ffffff').'">';
	}
	function cfa_title_color_cb(){
		echo '<input type="text" name="cfa_title_color" id="cfa_title_color" data-default-color="#333333" value="'.((get_option('cfa_title_color')) ? get_option('cfa_title_color') : '#333333').'">';
	}
	function cfa_card_title_font_size_cb(){
		echo '<input type="number" min="10" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')"name="cfa_card_title_font_size" placeholder="18px" value="'.get_option('cfa_card_title_font_size').'">';
	}
	function cfa_card_title_font_weight_cb(){
		echo '<input type="number" min="100" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')"name="cfa_card_title_font_weight" placeholder="700" value="'.get_option('cfa_card_title_font_weight').'">';
	}
	function cfa_single_title_font_size_cb(){
		echo '<input type="number" min="15" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')" name="cfa_single_title_font_size" placeholder="28px" value="'.get_option('cfa_single_title_font_size').'">';
	}
	function cfa_single_title_font_weight_cb(){
		echo '<input type="number" min="100" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')" name="cfa_single_title_font_weight" placeholder="700" value="'.get_option('cfa_single_title_font_weight').'">';
	}
	function cfa_date_color_cb(){
		echo '<input type="text" name="cfa_date_color" id="cfa_date_color" data-default-color="#E91934" value="'.((get_option('cfa_date_color')) ? get_option('cfa_date_color') : '#E91934').'">';
	}
	function cfa_date_font_size_cb(){
		echo '<input type="number" min="8" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')" name="cfa_date_font_size" placeholder="14px" value="'.get_option('cfa_date_font_size').'">';
	}
	function cfa_date_font_weight_cb(){
		echo '<input type="number" min="100" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')" name="cfa_date_font_weight" placeholder="500" value="'.get_option('cfa_date_font_weight').'">';
	}
	function cfa_content_text_color_cb(){
		echo '<input type="text" name="cfa_content_text_color" id="cfa_content_text_color" data-default-color="#646464" value="'.((get_option('cfa_content_text_color')) ? get_option('cfa_content_text_color') : '#646464').'">';
	}
	function cfa_content_font_size_cb(){
		echo '<input type="number" min="10" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')" name="cfa_content_font_size" placeholder="16px" value="'.get_option('cfa_content_font_size').'">';
	}
	function cfa_content_font_weight_cb(){
		echo '<input type="number" min="100" oninput="((this.value) ? this.value = Math.abs(this.value) : \'\')" name="cfa_content_font_weight" placeholder="100" value="'.get_option('cfa_content_font_weight').'">';
	}



	function menupage_callback(){
		require_once plugin_dir_path( __FILE__ )."partials/setting-page.php";
	}

	// Export event registrants
	function events_registrants_export(){
		if(isset($_GET['cfaction']) && $_GET['cfaction'] === 'export' && isset($_GET['post']) && !empty($_GET['post']) && is_admin(  )){
			global $wpdb;
			$event_id = intval($_GET['post']);
    		$registrants = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}cfa_registrants WHERE event_id = $event_id");

			if(is_array($registrants) && sizeof($registrants) > 0){ 
				$delimiter = ","; 
				$filename = "Registrants-" . date('Y-m-d') . ".csv"; 
				 
				// Create a file pointer 
				$f = fopen('php://memory', 'w'); 
				 
				// Set column headers 
				$fields = array('Name', 'Email', 'Phone', 'Company', 'Registered'); 
				fputcsv($f, $fields, $delimiter); 
				 
				// Output each row of the data, format line as csv and write to file pointer 
				foreach($registrants as $registrant){

					$lineData = array(
						$registrant->name, 
						$registrant->email, 
						$registrant->phone, 
						$registrant->company,
						date("F j, Y g:ia", strtotime($registrant->created))
					); 
					fputcsv($f, $lineData, $delimiter);

				} 
				 
				// Move back to beginning of file 
				fseek($f, 0); 
				 
				// Set headers to download file rather than displayed 
				header('Content-Type: text/csv'); 
				header('Content-Disposition: attachment; filename="' . $filename . '";'); 
				 
				//output all remaining data on a file pointer 
				fpassthru($f); 
				exit;
			}
			
			return;
		}
	}
}
