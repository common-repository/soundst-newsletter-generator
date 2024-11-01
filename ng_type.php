<?php 
add_action('init', 'ng_newsletters_type');
function ng_newsletters_type(){
	$labels = array(
			'name' => 'newsletter'
			,'singular_name' => 'Newsletter'
			,'add_new' => 'Add New'
			,'add_new_item' => 'Add New Newsletter'
			,'edit_item' => 'Edit Newsletter'
			,'new_item' => 'New Newsletter'
			,'view_item' => 'View Newsletter'
			,'search_items' => 'Search Newsletter'
			,'not_found' => ''
			,'not_found_in_trash' => ''
			,'parent_item_colon' => 'Parent'
			,'menu_name' => 'Archive'
	);
	$args = array(
			'labels' => $labels,
			'public' => true,
			'publicly_queryable' => true,
			'exclude_from_search'=>true,
			'show_ui' => true,
			'query_var' => true,
			'show_in_menu' => 'NGPluginPage',
			'rewrite' => array('slug' => 'ng_newsletters'),
			'capability_type' => 'post',
			'has_archive' => false,
			'menu_position' => null,
			'supports' => array('title')
	);
	register_post_type( 'ng_newsletters', $args );
}

add_action( 'add_meta_boxes', 'add_ng_newsletters_custom_box' );

function add_ng_newsletters_custom_box() {
	$screens = array( 'ng_newsletters');
	foreach ($screens as $screen) {
		add_meta_box(
		'ng_newsletters_sectionid',
		__( 'Newsletter Custom Edit Section', 'ng_newsletters_textdomain' ),
		'ng_newsletters_box',
		$screen
		);
	}
}

function ng_newsletters_box( $post ) {
	// Use nonce for verification
	wp_nonce_field( plugin_basename( __FILE__ ), 'ng_newsletters_noncename' );

	// The actual fields for data entry
	// Use get_post_meta to retrieve an existing value from the database and use the value for the form
	$value[ng_header] = get_post_meta( $post->ID, '_ng_header', true );
	$value[ng_l_sidebar] = get_post_meta( $post->ID, '_ng_l_sidebar', true );
	$value[ng_upper_html] = get_post_meta( $post->ID, '_ng_upper_html', true );
	$value[ng_above_posts] = get_post_meta( $post->ID, '_ng_above_posts', true );
	$value[ng_start_date] = get_post_meta( $post->ID, '_ng_start_date', true );
	$value[ng_end_date] = get_post_meta( $post->ID, '_ng_end_date', true );
	$value[ng_below_posts] = get_post_meta( $post->ID, '_ng_below_posts', true );
	$value[ng_lower_html] = get_post_meta( $post->ID, '_ng_lower_html', true );
	$value[ng_r_sidebar] = get_post_meta( $post->ID, '_ng_r_sidebar', true );
	$value[ng_footer] = get_post_meta( $post->ID, '_ng_footer', true );
	$value[ng_final_html] = get_post_meta( $post->ID, '_ng_final_html', true );
	
	//header ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_header_field">Include header</label>
		</div>
		<input type="radio" id="ng_header_field" name="ng_header_field" value="1" <?php if($value[ng_header] == "1") echo 'checked';?>/>Yes
		<input type="radio" id="ng_header_field1" name="ng_header_field" value="" <?php if($value[ng_header] == "") echo 'checked';?>/>No
		<div class="clear"></div>
	</div>
	
	<?php //left sidebar ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_l_sidebar_field">Include left sidebar</label>
		</div>
		<input type="radio" id="ng_l_sidebar_field" name="ng_l_sidebar_field" value="1" <?php if($value[ng_l_sidebar] == "1") echo 'checked';?>/>Yes
		<input type="radio" id="ng_l_sidebar_field1" name="ng_l_sidebar_field" value="" <?php if($value[ng_l_sidebar] == "") echo 'checked';?>/>No
		<div class="clear"></div>
	</div>
	
	<?php //body upper ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_upper_html_field">Include body section additional upper HTML</label>
		</div>
		<input type="radio" id="ng_upper_html_field" name="ng_upper_html_field" value="1" <?php if($value[ng_upper_html] == "1") echo 'checked';?>/>Yes
		<input type="radio" id="ng_upper_html_field1" name="ng_upper_html_field" value="" <?php if($value[ng_upper_html] == "") echo 'checked';?>/>No
		<div class="clear"></div>
	</div>
	
	<?php //text above posts ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_above_posts_field">Text/HTML to appear above posts (optional)</label>
		</div>
		<textarea id="ng_above_posts_field" name="ng_above_posts_field"><?=(esc_attr($value[ng_above_posts]))?></textarea>
		<div class="clear"></div>
	</div>
	
	<?php //published date ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_start_date_field">Post published date range:</label>
		</div>
		<input type="text" id="ng_start_date_field" name="ng_start_date_field" value="<?=(esc_attr($value[ng_start_date]))?>"/> to
		<input type="text" id="ng_end_date_field" name="ng_end_date_field" value="<?=(esc_attr($value[ng_end_date]))?>"/> (mm/dd/yyyy or leave blank for no posts)
		<div class="clear"></div>
	</div>
	
	<?php //text below posts ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_below_posts_field">Text/HTML to appear below posts (optional)</label>
		</div>
		<textarea id="ng_below_posts_field" name="ng_below_posts_field"><?=(esc_attr($value[ng_below_posts]))?></textarea>
		<div class="clear"></div>
	</div>
	
	<?php //body lower ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_lower_html_field">Include body section additional lower HTML</label>
		</div>
		<input type="radio" id="ng_lower_html_field" name="ng_lower_html_field" value="1" <?php if($value[ng_lower_html] == "1") echo 'checked';?>/>Yes
		<input type="radio" id="ng_lower_html_field1" name="ng_lower_html_field" value="" <?php if($value[ng_lower_html] == "") echo 'checked';?>/>No
		<div class="clear"></div>
	</div>
	
	<?php //right sidebar ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_r_sidebar_field">Include right sidebar</label>
		</div>
		<input type="radio" id="ng_r_sidebar_field" name="ng_r_sidebar_field" value="1" <?php if($value[ng_r_sidebar] == "1") echo 'checked';?>/>Yes
		<input type="radio" id="ng_r_sidebar_field1" name="ng_r_sidebar_field" value="" <?php if($value[ng_r_sidebar] == "") echo 'checked';?>/>No
		<div class="clear"></div>
	</div>

	<?php //footer ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_footer_field">Include Footer</label>
		</div>
		<input type="radio" id="ng_footer_field" name="ng_footer_field" value="1" <?php if($value[ng_footer] == "1") echo 'checked';?>/>Yes
		<input type="radio" id="ng_footer_field1" name="ng_footer_field" value="" <?php if($value[ng_footer] == "") echo 'checked';?>/>No
		<div class="clear"></div>
	</div>
	
	<?php //final html ?>
	<div class="edit-space">
		<div class="edit-name">
			<label for="ng_final_html_field">HTML (copy/paste this into your newsletter)</label>
		</div>
		<textarea id="ng_final_html_field" name="ng_final_html_field"><?php if(($post->post_status == 'publish')) {require_once(dirname(__FILE__).'/final_html.php');}?></textarea>
		<div class="clear"></div>
	</div>
<?php 
}

add_action( 'save_post', 'newsletter_custom_save_postdata' );
function newsletter_custom_save_postdata( $post_id ) {
	// First we need to check if the current user is authorised to do this action.
	if ( 'ng_newsletters' == $_POST['post_type'] ) {
		if ( ! current_user_can( 'edit_page', $post_id ) )
			return;
	} else {
		if ( ! current_user_can( 'edit_post', $post_id ) )
			return;
	}

	// Secondly we need to check if the user intended to change this value.
	if ( ! isset( $_POST['ng_newsletters_noncename'] ) || ! wp_verify_nonce( $_POST['ng_newsletters_noncename'], plugin_basename( __FILE__ ) ) )
		return;

	// Thirdly we can save the value to the database

	//if saving in a custom table, get post_ID
	$post_ID = $_POST['post_ID'];
	
	//header
	$mydata[ng_header] = sanitize_text_field( $_POST['ng_header_field'] );
	add_post_meta($post_ID, '_ng_header', $mydata[ng_header], true) or
	update_post_meta($post_ID, '_ng_header', $mydata[ng_header]);
	
	//left sidebar
	$mydata[ng_l_sidebar] = sanitize_text_field( $_POST['ng_l_sidebar_field'] );
	add_post_meta($post_ID, '_ng_l_sidebar', $mydata[ng_l_sidebar], true) or
	update_post_meta($post_ID, '_ng_l_sidebar', $mydata[ng_l_sidebar]);
	
	//upper html
	$mydata[ng_upper_html] = sanitize_text_field( $_POST['ng_upper_html_field'] );
	add_post_meta($post_ID, '_ng_upper_html', $mydata[ng_upper_html], true) or
	update_post_meta($post_ID, '_ng_upper_html', $mydata[ng_upper_html]);
	
	//above posts
	$mydata[ng_above_posts] =  $_POST['ng_above_posts_field'];
	add_post_meta($post_ID, '_ng_above_posts', $mydata[ng_above_posts], true) or
	update_post_meta($post_ID, '_ng_above_posts', $mydata[ng_above_posts]);
	
	//start date
	$mydata[ng_start_date] = sanitize_text_field( $_POST['ng_start_date_field'] );
	add_post_meta($post_ID, '_ng_start_date', $mydata[ng_start_date], true) or
	update_post_meta($post_ID, '_ng_start_date', $mydata[ng_start_date]);
	
	//end date
	$mydata[ng_end_date] = sanitize_text_field( $_POST['ng_end_date_field'] );
	add_post_meta($post_ID, '_ng_end_date', $mydata[ng_end_date], true) or
	update_post_meta($post_ID, '_ng_end_date', $mydata[ng_end_date]);
	
	//below posts
	$mydata[ng_below_posts] = $_POST['ng_below_posts_field'];
	add_post_meta($post_ID, '_ng_below_posts', $mydata[ng_below_posts], true) or
	update_post_meta($post_ID, '_ng_below_posts', $mydata[ng_below_posts]);
	
	//lower html
	$mydata[ng_lower_html] = sanitize_text_field( $_POST['ng_lower_html_field'] );
	add_post_meta($post_ID, '_ng_lower_html', $mydata[ng_lower_html], true) or
	update_post_meta($post_ID, '_ng_lower_html', $mydata[ng_lower_html]);
	
	//right sidebar
	$mydata[ng_r_sidebar] = sanitize_text_field( $_POST['ng_r_sidebar_field'] );
	add_post_meta($post_ID, '_ng_r_sidebar', $mydata[ng_r_sidebar], true) or
	update_post_meta($post_ID, '_ng_r_sidebar', $mydata[ng_r_sidebar]);
	
	//footer
	$mydata[ng_footer] = sanitize_text_field( $_POST['ng_footer_field'] );
	add_post_meta($post_ID, '_ng_footer', $mydata[ng_footer], true) or
	update_post_meta($post_ID, '_ng_footer', $mydata[ng_footer]);
	
	//final html
	$mydata[ng_final_html] = $_POST['ng_final_html_field'];
	add_post_meta($post_ID, '_ng_final_html', $mydata[ng_final_html], true) or
	update_post_meta($post_ID, '_ng_final_html', $mydata[ng_final_html]);
}

//*
//* ng_headlines columns
function modify_ng_newsletters_columns($posts_columns) {
	$posts_columns = array(
			"cb" => "cb",
			"title" => "Newsletter title",
			"date" => "Creation date",

	);
	return $posts_columns;
}
add_filter('manage_ng_newsletters_posts_columns', 'modify_ng_newsletters_columns');