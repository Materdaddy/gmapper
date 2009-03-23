<?php
/*
Plugin Name: Google Maps Plugin
Plugin URI: http://matmrosko.com/
Description: Add a google map to a wordpress page which contains pictures with GPS exif data.
Author: mat@matandtiff.com
Version: 1.0
Author URI: http://matmrosko.com/
License : http://matmrosko.com/ (Will GPL/MIT it)
*/

/***************************************
 * Plugin includes, and global variables - Begin
 **************************************/
//require('event.inc.php');
//require('date.inc.php');
//require('attendence.inc.php');

// Variable for debugging.  Change to "text" to enable debugging on page output.
$hidden = 'hidden';

/***************************************
 * Plugin includes, and global variables - End
 **************************************/

/***************************************
 * Main Plugin functionality - Begin
 **************************************/
if ( !function_exists('event_install') ) {
function event_install()
{
//	global $wpdb;
//	global $event_db_version;
//
//	$table1_name = $wpdb->prefix . "events";
//	$table2_name = $wpdb->prefix . "attendence_map";
//	$table3_name = $wpdb->prefix . "event_categories";
//	if ($wpdb->get_var("show tables like '$table1_name'") != $table1_name ||
//		$wpdb->get_var("show tables like '$table2_name'") != $table2_name ||
//		$wpdb->get_var("show tables like '$table3_name'") != $table3_name ||
//		$event_db_version != get_option("event_db_version") )
//	{
//		require_once(ABSPATH . 'wp-admin/upgrade-functions.php');
//		$sql = "CREATE TABLE " . $table1_name . " (
//			id INTEGER NOT NULL auto_increment,
//			event_name VARCHAR(96) NOT NULL,
//			event_description VARCHAR(255) NOT NULL,
//			event_category INTEGER NOT NULL,
//			event_date DATETIME NOT NULL,
//			UNIQUE KEY id (id),
//			primary key (id)
//			);";
//
//		dbDelta($sql);
//
//		$sql = "CREATE TABLE " . $table2_name . " (
//			user_id INTEGER NOT NULL,
//			event_id INTEGER NOT NULL,
//			FOREIGN KEY (event_id) references " . $table1_name . "(id),
//			FOREIGN KEY (user_id) references wp_users(ID),
//			UNIQUE KEY (user_id, event_id)
//			);";
//
//		dbDelta($sql);
//
//		$sql = "CREATE TABLE " . $table3_name . " (
//			category_id INTEGER NOT NULL auto_increment,
//			category_name VARCHAR(96) NOT NULL,
//			UNIQUE KEY (category_id)
//			);";
//
//		dbDelta($sql);
//
//		$sql = "INSERT INTO " . $table3_name .
//				" (category_name) VALUES " .
//				"(\"Conference\"), " .
//				"(\"Outreach Event\"), " .
//				"(\"Meeting\"), " .
//				"(\"Grant Meeting\");";
//		$wpdb->query($sql);
//
//		add_option("event_db_version", $event_db_version);
//	}
}}

if ( !function_exists('event_uninstall') ) {
function event_uninstall()
{
//	if (get_option("event_remove_upon_uninstall") == "on")
//	{
//		global $wpdb;
//
//		// Axe the database
//		$table1_name = $wpdb->prefix . "events";
//		$table2_name = $wpdb->prefix . "attendence_map";
//		$sql = "DROP TABLE " . $table1_name . ";";
//		$wpdb->query( $sql );
//		$sql = "DROP TABLE " . $table2_name . ";";
//		$wpdb->query( $sql );
//
//		// Remove all specific options
//		delete_option("event_db_version");
//		delete_option("event_remove_upon_uninstall");
//	}
}}

if ( !function_exists('event_process_updates') ) {
function event_process_updates($edit)
{
//	$return_string = "";
//	$name = $_POST['event_name'];
//	$description = $_POST['event_description'];
//	$category = $_POST['event_category'];
//
//	if ( !preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $_POST['date'], $date_matches) )
//		return "Bad date format, please enter in 'MM/DD/YYYY' (i.e. 8/27/2007) format!";
//
//	$month = $date_matches[1];
//	$day = $date_matches[2];
//	$year = $date_matches[3];
//
//	if ( !preg_match('/^(\d{1,2})\:(\d{1,2})\s(.*)$/', $_POST['time'], $time_matches) )
//		return "Bad time format, please enter in 'hh:MM am/pm' (i.e. 2:30 pm) format!";
//
//	$hours = $time_matches[1];
//	$minutes = $time_matches[2];
//	$ampm = strtolower($time_matches[3]);
//
//	$date = createDate( $year, $month, $day, $hours, $minutes, $ampm );
//
//	if ( !$date )
//		return "Bad Date or Time!";
//
//	if ( $edit )
//	{
//		$event = getSpecificMeecEvent( $_POST['event_edit'] );
//		if ( $event )
//			$return_string .= $event->update( $name, $description, $category, $date );
//		$event = null;
//	}
//	else
//	{
//		$event = new MeecEvent( $name, $description, $category, $date );
//		if ( !$event )
//			$return_string .= "Error creating event!";
//		elseif ( $event && !$event->addToDb() )
//			$return_string .= "Error inserting information into database!";
//		else
//			$return_string .= "Added $name to the event list!";
//		$event = null;
//	}
//
//	return $return_string;
}}

//if ( !function_exists('event_admin_header') ) {
//function event_admin_header()
//{
//	echo "<script type='text/javascript' src='" . rtrim(get_settings('siteurl'), '/') .
//		 "/wp-content/plugins/events_plugin/CalendarPopup.js'></script>\n";
//	echo "<link rel='stylesheet' type='text/css' href='" . rtrim(get_settings('siteurl'), '/') .
//		 "/wp-content/plugins/events_plugin/style.css'>\n";
//}}

if ( !function_exists('google_maps_page_search') ) {
function google_maps_page_search($document)
{
	if ( !preg_match('/<!--google-map-\d*x\d*-->/', $document) )
		return $document;

	global $google_maps_images;
	$google_maps_images = null;

	if ( preg_match_all('/(<a href.*>)(<img src=.*\/>)(<\/a>)/', $document, $matches) )
	{
		if ( !empty( $matches[1] ) )
		{
			//build image list
			foreach ( $matches[2] as $image )
			{
				// Pull out image url from 'src' and make sure it contains this blog's url before even attempting
				// to match it in the map function.
				if ( ( false !== $image_url = preg_replace('/<img src=\"?\'?([^"\']*)\"?\'?.*>/', "$1", $image)) &&
								preg_match( '/'.preg_replace('/\//', '\/', get_option('siteurl')).'/', $image_url) )
					$google_maps_images[] = $image_url;
			}
		}
	}

	$regex = '/<!--google-map-(\d*)x(\d*)-->/';
	$document = preg_replace_callback($regex, 'google_maps_page', $document);

	return $document;
}}

if ( !function_exists('sexagesimal_to_float') ) {
function sexagesimal_to_float($x) {
	eval('$f = ' . $x[0] . "+" . $x[1] . " / 60 + "  . $x[2] . " / 3600;");
	return $f;
}}

if ( !function_exists('google_maps_page') ) {
function google_maps_page($matches)
{
	global $post, $google_maps_images;
	$__google_maps_images = Array();

	if ( $google_maps_images == null )
		return $matches[0];

	$doc = "";

	$attachments = get_posts("post_parent=".$post->ID."&numberposts=0&post_type=attachment&post_status=inherit");
	if ( $attachments )
	{
		$i = 0;
		foreach ( $attachments as $attachment )
		{
			if ( !$imagedata = wp_get_attachment_metadata( $attachment->ID ) )
				continue;

			$thumb_url = wp_get_attachment_thumb_url( $attachment->ID );
			$big_url = wp_get_attachment_url( $attachment->ID );
			if ( !in_array( $thumb_url, $google_maps_images ) && !in_array( $big_url, $google_maps_images ))
				continue;

			$attachment_file = get_attached_file( $attachment->ID );
			$exif_data = exif_read_data($attachment_file);

			$exif_desc = $exif_data["ImageDescription"];
			$exif_datetime = $exif_data["DateTimeOriginal"];
			$exif_lat = $exif_data["GPSLatitude"];
			$exif_lon = $exif_data["GPSLongitude"];
			if ( empty($exif_lat) || empty($exif_lon) )
			{
				echo "NO exif<br />\n";
				continue;
			}

			$lat = sexagesimal_to_float($exif_lat);
			if ($exif_data["GPSLatitudeRef"][0] == "S")
				$lat = -$lat;
			$lon = sexagesimal_to_float($exif_lon);
			if ($exif_data["GPSLongitudeRef"][0] == "W")
				$lon = -$lon;

			$__google_maps_images[$i]['lat'] = $lat;
			$__google_maps_images[$i]['lon'] = $lon;
			$__google_maps_images[$i]['thumb'] = $thumb_url;
			$__google_maps_images[$i++]['desc'] = $exif_desc;
		}
	}

	$width = intval($matches[1]);
	$height = intval($matches[2]);

 	$doc .= "<div id=\"wp-gmapper-map\" style=\"width: ".$width."px; height: ".$height."px\"></div>\n";

	$doc .= "<script type=\"text/javascript\">\n";
	$doc .= "<!--\n";
	$doc .= "var google_maps_images = new Array();\n";
	for ( $i = 0; $i < count($__google_maps_images); $i += 1 )
	{
		$doc .= "google_maps_images[".$i."] = new Array(\"".$__google_maps_images[$i]['lat']."\",\"".
															$__google_maps_images[$i]['lon']."\",\"".
															$__google_maps_images[$i]['thumb']."\",\"".
															$__google_maps_images[$i]['desc'].
															"\");\n";
	}
	$doc .= "//-->\n";
	$doc .= "</script>\n";

	return $doc;
}}

if ( !function_exists('google_maps_options_page') ) {
function google_maps_options_page()
{
//	global $hidden;
//	if ( $hidden == "text" )
//	{
//		print_r($_POST);
//		print_r($_FILES);
//	}
//
//	if ( isset($_POST['event_delete']))
//	{
//		$event = getSpecificMeecEvent($_POST['event_delete']);
//		if ( $event )
//			$event->delete();
//		echo "<div id='message' class='updated fade'><p><strong>Deleted ".$event->getName()."</strong></p></div>\n";
//		$event = null;
//	}
//
//	if (isset($_POST['event_submit']))
//	{
//		$doc = "";
//		$doc = event_process_updates(isset($_POST['event_edit']));
//
//		if ( $doc )
//		{
//			echo "<div id='message' class='updated fade'><p><strong>";
//			echo $doc;
//			echo "</strong></p></div>\n";
//		}
//	}
//
//	echo "<script language='JavaScript'>\n";
//	echo "var now = new Date();\n";
//	echo "var cal = new CalendarPopup('cal');\n";
//	echo "cal.addDisabledDates(null,formatDate(now,'yyyy-MM-dd'));\n";
//	echo "</script>\n";
//
//	if (isset($_POST['event_edit']))
//	{
//		$event = getSpecificMeecEvent($_POST['event_edit']);
//		if ( $event )
//			echo $event->getEditHTML();
//		$event = null;
//	}
//	else
//	{
//		echo "<div class='wrap'>\n";
//		echo "<form action='".$_SERVER['REQUEST_URI']."' name='event_form_add' id='event_form_add' method='post' enctype='multipart/form-data'>\n";
//		echo "<input type='$hidden' id='event_submit' name='event_submit'>\n";
//		echo "<h2>Add an Event</h2>\n";
//		echo "<table cellpadding=0 cellspacing=0 border=0>\n";
//		echo "<tr><td>Event Name:</td><td><input type='text' id='event_name' name='event_name'></td></tr>\n";
//		echo "<tr><td>Event Description:</td><td><textarea cols=18 rows=5 id='event_description' name='event_description'></textarea></td></tr>\n";
//		echo "<tr><td>Event Category:</td><td><select id='event_category' name='event_category'>";
//
//		$categories = getMeecEventCategories();
//		if ( $categories ) {
//		foreach ( $categories as $category )
//			echo "<option value=\"".$category['category_id']."\">".$category['category_name'];}
//		echo "</select></td></tr>\n";
//		echo "<tr><td>Date (MM/DD/YYYY):</td><td>";
//		echo "<div id='cal' style='position:absolute;visibility:hidden;background-color:white;layer-background-color:white;'></div>";
//		echo "<input type='text' id='date' name='date'><a href='#'";
//		echo "onClick=\"cal.select(document.forms['event_form_add'].date,'cal_anchor','MM/dd/yyyy'); ";
//		echo "return false;\" name='cal_anchor' id='cal_anchor'>Select Date</a></td></tr>\n";
//		echo "<tr><td>Time (HH:MM am/pm):</td><td><input type='text' id='time' name='time'></td></tr>\n";
//		echo "</table>\n";
//		echo "<p><a class=\"admin_button reload_button\" href='".$_SERVER['REQUEST_URI']."'>Reload Changes</a></p>\n";
//		echo "<p><a href='#' class=\"admin_button submit_button\" onClick='document.event_form_add.submit()'>Submit Changes</a></p>";
//		echo "<p><a href='#' class=\"admin_button clear_button\" onClick='document.event_form_add.reset()'>Clear Form</a></p><br />";
//		echo "</form>\n";
//
//		echo "<p><h2>Edit/Delete event</h2>\n";
//
//		echo "<h3>Future Events</h3>\n";
//		$events = getMeecEventsFuture();
//		if ( $events )
//		{
//			foreach ($events as $event)
//			{
//				echo $event->getAdminHTML();
//				$event = null;
//			}
//		}
//
//		echo "<h3>Past Events</h3>\n";
//		$events = getMeecEventsPast();
//		if ( $events )
//		{
//			foreach ($events as $event)
//			{
//				echo $event->getAdminHTML();
//				$event = null;
//			}
//		}
//	}
//
//	echo "</div>";
}}

if ( !function_exists('google_maps_header') ) {
function google_maps_header()
{
	$google_maps_api_key = get_option('google_maps_api_key');
	$google_maps_api_key = "ABQIAAAA4-ufYXBswT8FT6ypIbNO5BRmA5ZTSk2_ZJ81_fMaUFmK_hXYjxRkR-5_0rv6176ZIpZTh7habYdAgQ";

	echo "<script src=\"http://maps.google.com/maps?file=api&amp;v=2&amp;key=" .
		$google_maps_api_key."\" type=\"text/javascript\"></script>\n";
	echo "<script type='text/javascript' src='" . rtrim(get_settings('siteurl'), '/') .
		 "/wp-content/plugins/wp-gmapper/wp-gmapper.js'></script>\n";
}}

if ( !function_exists('google_maps_footer') ) {
function google_maps_footer()
{
	echo "<script type=\"text/javascript\">\n";
	echo "load();\n";
	echo "</script>\n";
}}
/***************************************
 * Main Plugin functionality - End
 **************************************/

/***************************************
 * Wordpress hooks - Begin
 **************************************/
if ( !function_exists('google_maps_admin_page') ) {
function google_maps_admin_page()
{
	add_options_page('GMapper Options', 'GMapper Options', 8, 'google_maps_options', 'google_maps_options_page');
}}

if ( function_exists('add_action') ) {
//preg_match('/^.*\/wp-content\/plugins\/(.*)$/', __FILE__, $matches);
//$filename = $matches[1];
add_action('admin_menu', 'google_maps_admin_page');
//add_action('activate_'.$filename, 'event_install');
//add_action('deactivate_'.$filename, 'event_uninstall');
//add_action('admin_head', 'event_admin_header');
}

if ( function_exists('add_filter') ) {
add_filter('the_content', 'google_maps_page_search' );
add_action('wp_head', 'google_maps_header');
add_action('wp_footer', 'google_maps_footer');
}
/***************************************
 * Wordpress hooks - End
 **************************************/

?>
