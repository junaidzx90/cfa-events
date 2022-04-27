<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.example.com/unknown
 * @since      1.0.0
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Cfa_Events
 * @subpackage Cfa_Events/public
 * @author     Developer Junayed <admin@easeare.com>
 */
class Cfa_Events_Public {

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

	private $icsName;

	private $icsData;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		add_shortcode( 'latest_events', [$this, 'latest_events'] );
		add_shortcode( 'previous_events', [$this, 'previous_events'] );
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
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
		global $post;
		
		if ( (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'latest_events') || has_shortcode( $post->post_content, 'previous_events') ) || is_singular( 'events' ) ) {
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/style.css', array(), $this->version, 'all' );
		}
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		global $post;

		if (!is_admin(  ) && (is_a( $post, 'WP_Post' ) && has_shortcode( $post->post_content, 'latest_events') || has_shortcode( $post->post_content, 'previous_events') ) || is_singular( 'events' ) ) {
			wp_enqueue_script( 'cfavue', plugin_dir_url( __FILE__ ) . 'js/vue.min.js', array(  ), $this->version, false );
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/index.js', array( 'jquery', 'cfavue' ), $this->version, true );
			wp_localize_script( $this->plugin_name, 'cfa_ajax', array(
				'ajaxurl' => admin_url( "admin-ajax.php" ),
				'nonce'	=> wp_create_nonce( "cfa_nonce" )
			) );
		}
	}

	function wp_head_scripts(){
		?>
		<style>
			:root{
				--cfa_static_color: <?php echo ((get_option('cfa_static_color')) ? get_option('cfa_static_color') : '#3E3F94') ?>;
				--cfa_static_text_color: <?php echo ((get_option('cfa_static_text_color')) ? get_option('cfa_static_text_color') : '#FFFFFF') ?>;
				--cfa_selected_color: <?php echo ((get_option('cfa_selected_color')) ? get_option('cfa_selected_color') : '#8FD9F9') ?>;
				--cfa_selected_text_color: <?php echo ((get_option('cfa_selected_text_color')) ? get_option('cfa_selected_text_color') : '#3E3F94') ?>;
				--cfa_title_color: <?php echo ((get_option('cfa_title_color')) ? get_option('cfa_title_color') : '#333333') ?>;
				--cfa_card_title_font_size: <?php echo ((get_option('cfa_card_title_font_size')) ? get_option('cfa_card_title_font_size').'px' : '18px') ?>;
				--cfa_card_title_font_weight: <?php echo ((get_option('cfa_card_title_font_weight')) ? get_option('cfa_card_title_font_weight') : '700') ?>;
				--cfa_single_title_font_size: <?php echo ((get_option('cfa_single_title_font_size')) ? get_option('cfa_single_title_font_size').'px' : '28px') ?>;
				--cfa_single_title_font_weight: <?php echo ((get_option('cfa_single_title_font_weight')) ? get_option('cfa_single_title_font_weight') : '700') ?>;
				--cfa_date_color: <?php echo ((get_option('cfa_date_color')) ? get_option('cfa_date_color') : '#E91934') ?>;
				--cfa_date_font_size: <?php echo ((get_option('cfa_date_font_size')) ? get_option('cfa_date_font_size').'px' : '14px') ?>;
				--cfa_date_font_weight: <?php echo ((get_option('cfa_date_font_weight')) ? get_option('cfa_date_font_weight') : '500') ?>;
				--cfa_content_text_color: <?php echo ((get_option('cfa_content_text_color')) ? get_option('cfa_content_text_color') : '#646464') ?>;
				--cfa_content_font_size: <?php echo ((get_option('cfa_content_font_size')) ? get_option('cfa_content_font_size').'px' : '16px') ?>;
				--cfa_content_font_weight: <?php echo ((get_option('cfa_content_font_weight')) ? get_option('cfa_content_font_weight') : '100') ?>;
			}
		</style>
		<?php
	}

	function latest_events(){
		ob_start();
		require_once plugin_dir_path( __FILE__ )."partials/latest-events.php";
		$output = ob_get_contents();
		ob_get_clean();
		return $output;
	}

	function previous_events(){
		if(is_admin(  )){
			ob_start();
			require_once plugin_dir_path( __FILE__ )."partials/elementor-preview.php";
			$output = ob_get_contents();
			ob_get_clean();
			return $output;
		}else{
			ob_start();
			require_once plugin_dir_path( __FILE__ )."partials/archive-events.php";
			$output = ob_get_contents();
			ob_get_clean();
			return $output;
		}
	}

	function template_redirect($template){

		if ( is_singular( 'events' )) {
			$theme_files = array('single-event.php', plugin_dir_path( __FILE__ ).'partials/single-event.php');
			$exists_in_theme = locate_template($theme_files, false);
			if ( $exists_in_theme != '' ) {
				$template = $exists_in_theme;
			} else {
				$template = plugin_dir_path( __FILE__ ). 'partials/single-event.php';
			}
		}

		if ($template == '') {
			throw new \Exception('No template found');
		}

		return $template;
	}

	// Email template
	function email_template($title, $contents, $footer){
		$template = '<body bgcolor="#f5f5f5" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" offset="0" style="padding:70px 0 70px 0;">';
		$template .= '<table width="600" height="auto" align="center" cellpadding="0" cellspacing="0" style="background-color:#fdfdfd; border:1px solid #dcdcdc; border-radius:3px !important;">';

		if(!empty($title)){
			$template .= '<tr>';
			$template .= '<td width="600" height="auto" bgcolor="#557da1" border="0" style="padding:36px 48px; display:block; margin: 0px auto;">';
			// Heading
			$template .= '<h1 style="color:#ffffff; font-family:&quot; Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif; font-size:30px; line-height:150%; font-weight:300; margin:0; text-align:left;">'.$title.'</h1>';
			$template .= '</td>';
			$template .= '</tr>';
		}

		$template .= '<tr>';
		$template .= '<td width="600" bgcolor="#fdfdfd" border="0" style="color:#737373; font-family:&quot;Helvetica Neue&quot;,Helvetica,Roboto,Arial,sans-serif; font-size:14px; line-height:150%; text-align:left; padding:48px;">';
		// Contents
		$template .= wpautop($contents, true);
		$template .= '</td>';
		$template .= '</tr>';

		if(!empty($footer)){
			$template .= '<tr>';
			$template .= '<td width="600" border="0" style="padding:0 48px 48px 48px; color:#707070; font-family:Arial; font-size:12px; line-height:125%; text-align:center;">';
			// Footer
			$template .= '<p>'.wpautop( $footer, true ).'</p>';
			$template .= '</td>';
			$template .= '</tr>';
		}

		$template .= '</table>';
		$template .= '</body>';

		return $template;
	}

	function send_email_notification($data){
		$to = $data['to'];
		$subject = $data['subject'];
		$body = $this->email_template($data['title'], $data['contents'], $data['footer']);
		$headers = array('Content-Type: text/html; charset=UTF-8');
		 
		wp_mail( $to, $subject, $body, $headers );
	}

	// Registration submission
	function registrants_register(){
		if(!wp_verify_nonce( $_POST['nonce'], 'cfa_nonce' )){
			die("Invalid request!");
		}

		if(isset($_POST['data'])){
			$data = $_POST['data'];
			$event_id = intval($data['event_id']);
			$registrant_name = sanitize_text_field($data['registrant_name']);
			$registrant_email = sanitize_email($data['registrant_email']);
			$registrant_phone = intval($data['registrant_phone']);

			if(!empty($registrant_name) && !empty($registrant_email) && !empty($registrant_phone)){
				global $wpdb;
				$defaultZone = wp_timezone_string();
				date_default_timezone_set($defaultZone);

				if(!$wpdb->get_var("SELECT ID FROM {$wpdb->prefix}cfa_registrants WHERE event_id = $event_id AND email = '$registrant_email' AND phone = '$registrant_phone'")){
					$wpdb->insert($wpdb->prefix.'cfa_registrants', array(
						'event_id' => $event_id,
						'name' => $registrant_name,
						'email' => $registrant_email,
						'phone' => $registrant_phone,
						'created' => date("Y-m-d h:i:s")
					), array("%d", "%s", "%s", "%d", "%s"));
	
					$_SESSION['event_registrants'] = $event_id;

					$eml_title = sanitize_text_field( get_post_meta($event_id, 'cfa_email_title' , true) );
					$email_subject = sanitize_text_field( get_post_meta($event_id, 'cfa_email_subject' , true) );
					$email_contents = get_post_meta($event_id, 'cfa_email_contents' , true);
					$email_footer = get_post_meta($event_id, 'cfa_email_footer' , true);
					
					if($email_subject && $email_contents){
						$emaildata = array(
							'title' => $eml_title,
							'subject' => $email_subject,
							'contents' => $email_contents,
							'footer' => $email_footer,
							'to' => $registrant_email
						);
						$this->send_email_notification($emaildata); // Sent email
					}
	
					echo json_encode(array("success" => "We are looking forward to seeing you at our event. You will recieve a confirmation email for your registration shortly.
					If you haven't already, add this event to your calendar using the links above to make sure you don't miss it."));
				
					die;
				}else{
					echo json_encode(array("error" => "Trying to re-submit the form!"));
					die;	
				}
			}else{
				echo json_encode(array("error" => "All fields are required!"));
				die;
			}
		}
	}

	// Ics file generator
	function icsFileGenerator($start, $end, $name, $description, $location) {
		$defaultZone = wp_timezone_string();
		date_default_timezone_set($defaultZone);

		$format = 'Y-m-d H:i:s';
		$icalformat = 'Ymd\THis';
		$startTime = $start;        
		$endTime   = $end;

		$startTime = DateTime::createFromFormat($format, $startTime)->format($icalformat);
		$endTime = DateTime::createFromFormat($format, $endTime)->format($icalformat);

        $this->icsName = $name;
        $this->icsData = "BEGIN:VCALENDAR\nVERSION:2.0\nMETHOD:PUBLISH\nBEGIN:VEVENT\nDTSTART:".$startTime."\nDTEND:".$endTime."\nLOCATION:".$location."\nTRANSP: OPAQUE\nSEQUENCE:0\nUID:\nDTSTAMP:".date("Ymd\THis\Z")."\nSUMMARY:".$name."\nDESCRIPTION:".$description."\nPRIORITY:1\nCLASS:PUBLIC\nBEGIN:VALARM\nACTION:DISPLAY\nDESCRIPTION:Reminder\nEnd:VALARM\nEnd:VEVENT\nEnd:VCALENDAR\n";
    }

	// Download the ICS file
    function exportIcsFile() {
        header("Content-type:text/calendar");
        header('Content-Disposition: attachment; filename="'.$this->icsName.'.ics"');
        Header('Content-Length: '.strlen($this->icsData));
        Header('Connection: close');
        echo $this->icsData;
    }
	
	// Action button for download ICS File
	function downloadIcsFile(){
		if(isset($_GET['cfaction']) && $_GET['cfaction'] === 'cfa_event_calendar' && isset($_GET['event'])){
			if(empty($_GET['event'])){
				return;
			}

			$event_id = intval($_GET['event']);
			$event_title = get_the_title( $event_id );
			$event_description = get_post($event_id)->post_content;
			$event_description = sanitize_text_field( $event_description );

			$event_location = get_post_meta($event_id, '__event_location', true);
			$date = get_post_meta($event_id, '__event_date', true);
			if($date){
				$date = date("Y-m-d", strtotime($date));
			}

			$star_time = get_post_meta($event_id, '__event_start_time', true);
			$star_time = date("H:i:s", strtotime($star_time));
			$end_time = get_post_meta($event_id, '__event_end_time', true);
			$end_time = date("H:i:s", strtotime($end_time));

			$event = $this->icsFileGenerator("$date $star_time", "$date $end_time", $event_title, $event_description, $event_location);

			$this->exportIcsFile();
		}
	}

	// Get events
	function get_archive_events(){
		if(!wp_verify_nonce( $_GET['nonce'], 'cfa_nonce' )){
			die("Invalid Request!");
		}

		$page = 1;
		if(isset($_GET['page'])){
			$page = intval($_GET['page']);
		}

		$perpage = ((get_option('events_perpage')) ? intval(get_option('events_perpage')) : 12);

		$args = array(
			'post_type' => 'events',
			'post_status' => 'publish',
			'posts_per_page' => $perpage,
			'paged' => $page,
			'meta_key' => '__event_date',
			'orderby' => 'meta_value',
			'meta_type' => 'DATE',
			'order' => 'ASC',
		);

		if(isset($_GET['year']) && !empty($_GET['year']) && $_GET['year'] !== 'all'){
			$year = $_GET['year'];
			if(!empty($year)){
				$args['meta_query'] = array(
					array(
						'key' => '__event_date',
						'value' => $year,
						'compare' => 'LIKE'
					)
				);
			}
		}
	
		$previousEvents = array();
		$eventsObj = new WP_Query( $args );
		if ( $eventsObj->have_posts() ){
			while ( $eventsObj->have_posts() ){
				$eventsObj->the_post();

				$event_id = get_post()->ID;
				$post_title = get_the_title(  );
				$event_date = get_post_meta($event_id, '__event_date', true);
				if($event_date){
					$event_date = date("j F, Y", strtotime($event_date));
				}
				$location = get_post_meta($event_id, '__event_location', true);
				$thumbnail = ((get_the_post_thumbnail_url(  )) ? get_the_post_thumbnail_url(  ) : get_option('cfa_fallback_thumb') );

				$len = ((get_option('excerpt_length')) ? get_option('excerpt_length') : 10);
				$excerpt = wp_trim_words(get_the_excerpt( get_post()->ID ), $len);
				$permalink = get_the_permalink( get_post()->ID );

				$event = array(
					'event_id' => $event_id,
					'title' => $post_title,
					'thumbnail' => $thumbnail,
					'date' => (($event_date) ? $event_date : ''),
					'location' => $location,
					'excerpt' => $excerpt,
					'permalink' => $permalink
				);

				$previousEvents[] = $event;
			}
		}

		echo json_encode(array(
			'previousEvents' => $previousEvents,
			'maxpages' => $eventsObj->max_num_pages
		));
		die;
	}
}
