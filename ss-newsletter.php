<?php
/*
 Plugin Name: Soundst Newsletter Generator
Plugin URI: http://www.soundst.com/
Description: This plugin generates the HTML required by third-party newsletter services based on the rules set up by the administrator and a specific date range of posts
Version: 1.0.1
Author: Sound Strategies, Inc
Author URI: http://www.soundst.com/
*/

if(class_exists('ss_newsletter')){
	register_activation_hook(__FILE__, array( 'ss_newsletter', 'activate' ));
	register_deactivation_hook(__FILE__, array('ss_newsletter', 'deactivate'));

	$ss_newsletter = new ss_newsletter();
}

class ss_newsletter{
	public function __construct(){

		if(is_admin()){
			add_action('admin_menu', array($this, 'add_ng_page'),1);
			add_action('admin_init', array($this, 'page_init'));
			require_once(dirname(__FILE__).'/ng_type.php');
		}
	}

	public static function activate(){

	}
	public static function deactivate(){

	}

	public function add_ng_page(){
		wp_register_style( 'ng_stylesheet', plugins_url('styles.css', __FILE__) );
		wp_register_style( 'jquery-ui', 'http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css' );
		$user_id = get_current_user_id();
		$user = new WP_User($user_id);
		
		if (function_exists('add_menu_page')){
			add_menu_page( 'Soundst Newsletter - Settings', 'Newsletter', 'edit_posts', 'NGPluginPage', false, false, '64.4');
		}
		if (function_exists('add_submenu_page')){			
			
				add_submenu_page( 'NGPluginPage', 'Soundst Newsletter - Settings', 'Settings', 'manage_options', 'NGPluginPage', array($this, 'NGPluginPageOptions'));
		if($user->roles[0] != 'administrator') {
			add_action( 'admin_menu', 'remove_menu_pages', 999 );
			function remove_menu_pages() {
				remove_submenu_page('NGPluginPage', 'NGPluginPage');
			}	
		}						
			add_submenu_page( 'NGPluginPage', 'Soundst Newsletter - Create', 'Create', 'edit_posts', 'post-new.php?post_type=ng_newsletters');
		}
	}

	public function NGPluginPageOptions()
	{?>
<div class="wrap">
	<h2>Soundst Newsletter Generator</h2>
	<?php 
	if ($preview = get_option('ng_preview')) {?>
	<a href="#" id="popupdelay" style="display:none;">preview</a>
	<div class="popup pop">
	<a href="#" class="close" style="float:right;">Close</a>
	<script type="text/javascript">
		var delayseconds = 1;
		    function pause() {
		    myTimer = setTimeout('whatToDo()', delayseconds * 1000)
		    }
		   function whatToDo() {
		document.getElementById('popupdelay').click();
		    }
		window.onload = pause;
		</script>
	
	<?php 
	require_once(dirname(__FILE__).'/ng_preview.php');
	delete_option('ng_preview');
	?>
	</div>
	<?php }?>
	
	<form method="post" action="options.php">
		<p>This plugin generates the HTML required by third-party newsletter services based on the rules set up by the administrator and a specific date range of posts.</p>
		<?php
		// Prints out all hidden setting fields
		settings_fields('ng_option_group');
		do_settings_sections('ng-setting-admin');
		?>
		<?php submit_button('Update'); ?>
		<?php submit_button('Update & Preview','primary','keys[preview]');
	        ?>
		<?php settings_errors( 'settings-error' );?>
	</form>

</div>
<?php 
	}

	public function page_init()
	{
		/*
		 * Settings Plugin Page
		*/
		register_setting('ng_option_group', 'keys', array($this, 'check_ALL'));

		//*
		//* Settings Section
		add_settings_section(
		'settings_section',
		'Settings',
		array($this, 'settings_section_info'),
		'ng-setting-admin'
		);

		//* General Section
		add_settings_section(
		'general_section',
		'General Settings',
		false,
		'ng-setting-admin'
			);

		//Left sidebar width
		add_settings_field(
		'ls_width',
		'Left Sidebar width:',
		array($this, 'create_ls_width_field'),
		'ng-setting-admin',
		'general_section'
		);

		//Content width
		add_settings_field(
		'content_width',
		'Content width (pixels):',
		array($this, 'create_content_width_field'),
		'ng-setting-admin',
		'general_section'
		);

		//Right sidebar width
		add_settings_field(
		'rs_width',
		'Right Sidebar width:',
		array($this, 'create_rs_width_field'),
		'ng-setting-admin',
		'general_section'
		);

		//Padding between main blocks
		add_settings_field(
		'pbmb',
		'Padding (pixels):',
		array($this, 'create_pbmb_field'),
		'ng-setting-admin',
		'general_section'
				);

		//Main background color or image
		add_settings_field(
		'mbg',
		'Background color or image:',
		array($this, 'create_mbg_field'),
		'ng-setting-admin',
		'general_section'
				);

		//Main background color
		add_settings_field(
		'bgc',
		'Background color:',
		array($this, 'create_bgc_field'),
		'ng-setting-admin',
		'general_section'
					);

		//Main background image
		add_settings_field(
		'bgimg',
		'Background image:',
		array($this, 'create_bgimg_field'),
		'ng-setting-admin',
		'general_section'
					);

		//Main background image URL
		add_settings_field(
		'bgimg_url',
		'Background image URL:',
		array($this, 'create_bgimg_url_field'),
		'ng-setting-admin',
		'general_section'
			);

		//* Settings Section
		add_settings_section(
		'content_settings',
		'',
		array($this, 'content_settings_info'),
		'ng-setting-admin'
		);

		//Body font family
		add_settings_field(
		'font_family',
		'Font:',
		array($this, 'create_font_family_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//Body font size
		add_settings_field(
		'font_size',
		'Text size (pt):',
		array($this, 'create_font_size_field'),
		'ng-setting-admin',
		'content_settings'
	     );

		//Body font color
		add_settings_field(
		'text_color',
		'Text color:',
		array($this, 'create_text_color_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//Body link color
		add_settings_field(
		'link_color',
		'Link color:',
		array($this, 'create_link_color_field'),
		'ng-setting-admin',
		'content_settings'
		);
		//H1 font family
		add_settings_field(
		'h1_font_family',
		'H1 Text font:',
		array($this, 'create_h1_font_family_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H1 font size
		add_settings_field(
		'h1_font_size',
		'H1 Text size (pt):',
		array($this, 'create_h1_font_size_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H1 font color
		add_settings_field(
		'h1_text_color',
		'H1 Text color:',
		array($this, 'create_h1_text_color_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H1 link color
		add_settings_field(
		'h1_link_color',
		'H1 Link color:',
		array($this, 'create_h1_link_color_field'),
		'ng-setting-admin',
		'content_settings'
		);
		//H2 font family
		add_settings_field(
		'h2_font_family',
		'H2 Text font:',
		array($this, 'create_h2_font_family_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H2 font size
		add_settings_field(
		'h2_font_size',
		'H2 Text size (pt):',
		array($this, 'create_h2_font_size_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H2 font color
		add_settings_field(
		'h2_text_color',
		'H2 Text color:',
		array($this, 'create_h2_text_color_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H2 link color
		add_settings_field(
		'h2_link_color',
		'H2 Link color:',
		array($this, 'create_h2_link_color_field'),
		'ng-setting-admin',
		'content_settings'
		);
		//H3 font family
		add_settings_field(
		'h3_font_family',
		'H3 Text font:',
		array($this, 'create_h3_font_family_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H3 font size
		add_settings_field(
		'h3_font_size',
		'H3 Text size (pt):',
		array($this, 'create_h3_font_size_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H3 font color
		add_settings_field(
		'h3_text_color',
		'H3 Text color:',
		array($this, 'create_h3_text_color_field'),
		'ng-setting-admin',
		'content_settings'
		);

		//H3 link color
		add_settings_field(
		'h3_link_color',
		'H3 Link color:',
		array($this, 'create_h3_link_color_field'),
		'ng-setting-admin',
		'content_settings'
		);
		
		// <head> Section
		add_settings_section(
		'head_settings',
		'&lt;head&gt; Section Additional HTML',
		array($this, 'head_settings_info'),
		'ng-setting-admin'
				);
		
		// <head> HTML
		add_settings_field(
		'head_html',
		'&lt;head&gt; HTML',
		array($this, 'create_head_html_field'),
		'ng-setting-admin',
		'head_settings'
				);
		
		// Header Section
		add_settings_section(
		'header_settings',
		'Header Section Settings:',
		array($this, 'header_settings_info'),
		'ng-setting-admin'
		);
		//Header Type
		add_settings_field(
		'header_type',
		'Header type:',
		array($this, 'create_header_type_field'),
		'ng-setting-admin',
		'header_settings'
		);

		//Header image
		add_settings_field(
		'header_img',
		'Header image:',
		array($this, 'create_header_img_field'),
		'ng-setting-admin',
		'header_settings'
		);

		//Header url
		add_settings_field(
		'header_img_url',
		'Header image URL:',
		array($this, 'create_header_img_url_field'),
		'ng-setting-admin',
		'header_settings'
		);

		//Header html
		add_settings_field(
		'header_html',
		'Header HTML:',
		array($this, 'create_header_html_field'),
		'ng-setting-admin',
		'header_settings'
		);
		
		// <body> upper Section
		add_settings_section(
		'body_up_settings',
		'&lt;body&gt; Section Additional HTML (upper)',
		array($this, 'body_up_settings_info'),
		'ng-setting-admin'
		);

		// <body> upper
		add_settings_field(
		'body_up_html',
		'&lt;body&gt; HTML (upper)',
		array($this, 'create_body_up_html_field'),
		'ng-setting-admin',
		'body_up_settings'
		);
		// <body> lower Section
		add_settings_section(
		'body_low_settings',
		'&lt;body&gt; Section Additional HTML (lower)',
		array($this, 'body_low_settings_info'),
		'ng-setting-admin'
		);

		// <body> lower HTML
		add_settings_field(
		'body_low_html',
		'&lt;body&gt; HTML (lower)',
		array($this, 'create_body_low_html_field'),
		'ng-setting-admin',
		'body_low_settings'
		);
		// Left sidebar HTML Section
		add_settings_section(
		'left_s_html_settings',
		'Left Sidebar HTML Section Additional HTML',
		array($this, 'left_s_settings_info'),
		'ng-setting-admin'
		);

		// Left sidebar HTML
		add_settings_field(
		'left_s_html',
		'Left Sidebar HTML',
		array($this, 'create_left_s_html_field'),
		'ng-setting-admin',
		'left_s_html_settings'
		);

		// Right sidebar HTML Section
		add_settings_section(
		'right_s_html_settings',
		'Right Sidebar HTML Section Additional HTML',
		array($this, 'right_s_settings_info'),
		'ng-setting-admin'
		);

		// Right sidebar HTML
		add_settings_field(
		'right_s_html',
		'Right Sidebar HTML',
		array($this, 'create_right_s_html_field'),
		'ng-setting-admin',
		'right_s_html_settings'
		);
		
		// Footer Section
		add_settings_section(
		'footer_settings',
		'Footer Section Settings:',
		array($this, 'footer_settings_info'),
		'ng-setting-admin'
		);
		
		//Footer Type
		add_settings_field(
		'footer_type',
		'Footer type:',
		array($this, 'create_footer_type_field'),
		'ng-setting-admin',
		'footer_settings'
		);
		
		//Footer image
		add_settings_field(
		'footer_img',
		'Footer image:',
		array($this, 'create_footer_img_field'),
		'ng-setting-admin',
		'footer_settings'
		);
		
		//Footer url
		add_settings_field(
		'footer_img_url',
		'Footer image URL:',
		array($this, 'create_footer_img_url_field'),
		'ng-setting-admin',
		'footer_settings'
		);
		
		//Footer html
		add_settings_field(
		'footer_html',
		'Footer HTML:',
		array($this, 'create_footer_html_field'),
		'ng-setting-admin',
		'footer_settings'
		);

		// Post Settings Section
		add_settings_section(
		'post_settings',
		'Post Settings',
		array($this, 'post_settings_info'),
		'ng-setting-admin'
		);

		// Padding between posts
		add_settings_field(
		'posts_padding',
		'Padding between posts (pixels):',
		array($this, 'create_posts_padding_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Thumbnail width
		add_settings_field(
		'thumb_width',
		'Thumbnail width (pixels):',
		array($this, 'create_thumb_width_field'),
		'ng-setting-admin',
		'post_settings'
		);
		// Thumbnail alignment
		add_settings_field(
		'thumb_align',
		'Thumbnail alignment:',
		array($this, 'create_thumb_align_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Thumbnail padding
		add_settings_field(
		'thumb_pad',
		'Thumbnail padding (pixels):',
		array($this, 'create_thumb_pad_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Post Font
		add_settings_field(
		'posts_font',
		'Font:',
		array($this, 'create_posts_font_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Title text size
		add_settings_field(
		'title_size',
		'Title text size (pt):',
		array($this, 'create_title_size_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Title text color
		add_settings_field(
		'title_color',
		'Title text color:',
		array($this, 'create_title_color_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Date text size
		add_settings_field(
		'date_size',
		'Date text size (pt):',
		array($this, 'create_date_size_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Date text color
		add_settings_field(
		'date_color',
		'Date text color:',
		array($this, 'create_date_color_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Author text size
		add_settings_field(
		'author_size',
		'Author text size (pt):',
		array($this, 'create_author_size_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Author text color
		add_settings_field(
		'author_color',
		'Author text color:',
		array($this, 'create_author_color_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Post text font
		add_settings_field(
		'post_font',
		'Post text font:',
		array($this, 'create_post_font_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Post text size
		add_settings_field(
		'post_size',
		'Post text size (pt):',
		array($this, 'create_post_size_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Post text color
		add_settings_field(
		'post_color',
		'Post text color:',
		array($this, 'create_post_color_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Post text link color
		add_settings_field(
		'post_link_color',
		'Post text link color:',
		array($this, 'create_post_link_color_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Read more link text
		add_settings_field(
		'rm_text',
		'Read more link text:',
		array($this, 'create_rm_text_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Read more link size
		add_settings_field(
		'rm_size',
		'Read more link size (pt):',
		array($this, 'create_rm_size_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Read more link color
		add_settings_field(
		'rm_color',
		'Read more link color:',
		array($this, 'create_rm_color_field'),
		'ng-setting-admin',
		'post_settings'
		);

		// Post Layout HTML section
		add_settings_section(
		'post_layout',
		'Post Layout HTML',
		array($this, 'post_layout_info'),
		'ng-setting-admin'
		);

		// Post Layout HTML field
		add_settings_field(
		'layout_html',
		'Post Layout HTML:',
		array($this, 'create_layout_html_field'),
		'ng-setting-admin',
		'post_layout'
		);

		// Reset Default Post Layout HTML
		add_settings_field(
		'reset_layout',
		'Reset Default Post Layout HTML:',
		array($this, 'create_reset_layout_button'),
		'ng-setting-admin',
		'post_layout'
		);

		wp_enqueue_style( 'ng_stylesheet' );
		wp_enqueue_style( 'jquery-ui' );
		//wp_enqueue_media shoud run only for Theme options
		if ( (($_GET['page'] == 'NGPluginPage')||($_GET['post_type'] == 'ng_newsletters')) && !(did_action( 'wp_enqueue_media' )) ) {
			wp_enqueue_media();
			function add_media_script(){
				wp_enqueue_script( 'media_js', plugins_url('ss-newsletter-generator/media.js'),5);
				wp_enqueue_script( 'newsletter_js', plugins_url('ss-newsletter-generator/common.js'),array('jquery', 'jquery-ui-core', 'jquery-ui-tabs', 'jquery-ui-datepicker'));
			}
			add_action( 'admin_enqueue_scripts', 'add_media_script' );
		}
	}


	public function check_ALL($input){
		//*
		//*General Section

		// var_dump($input); die();

		if ($input['preview']) {
			add_option('ng_preview', '1');			
		} else {
			delete_option('ng_preview');
		}
		
		//Left sidebar width
		if(is_numeric($input['ls_width'])) {
			$mid['ls_width'] = $input['ls_width'];
			if(get_option('ng_ls_width') === FALSE)
			{
				add_option('ng_ls_width', $mid['ls_width']);
			}
			else
			{
				update_option('ng_ls_width', $mid['ls_width']);
			}
		}
		else
		{
			$mid['ls_width'] = '';
			update_option('ng_ls_width', $mid['ls_width']);
		}

		//Content width
		if(is_numeric($input['content_width'])) {
			$mid['content_width'] = $input['content_width'];
			if(get_option('ng_content_width') === FALSE)
			{
				add_option('ng_content_width', $mid['content_width']);
			}
			else
			{
				update_option('ng_content_width', $mid['content_width']);
			}
		}
		else
		{
			$mid['content_width'] = '';
		}

		//Right sidebar width
		if(is_numeric($input['rs_width'])) {
			$mid['rs_width'] = $input['rs_width'];
			if(get_option('ng_rs_width') === FALSE)
			{
				add_option('ng_rs_width', $mid['rs_width']);
			}
			else
			{
				update_option('ng_rs_width', $mid['rs_width']);
			}
		}
		else
		{
			$mid['rs_width'] = '';
			update_option('ng_rs_width', $mid['rs_width']);
		}

		//Padding between main blocks
		if(is_numeric($input['pbmb'])) {
			$mid['pbmb'] = $input['pbmb'];
			if(get_option('ng_pbmb') === FALSE)
			{
				add_option('ng_pbmb', $mid['pbmb']);
			}
			else
			{
				update_option('ng_pbmb', $mid['pbmb']);
			}
		}
		else
		{
			$mid['pbmb'] = '';
		}
		//Main background color or image
		$mid['mbg'] = $input['mbg'];
		if(get_option('ng_mbg') === FALSE)
		{
			add_option('ng_mbg', $mid['mbg']);
		}
		else
		{
			update_option('ng_mbg', $mid['mbg']);
		}

		//Main background color
		$mid['bgc'] = $input['bgc'];
		if(get_option('ng_bgc') === FALSE)
		{
			add_option('ng_bgc', $mid['bgc']);
		}
		else
		{
			update_option('ng_bgc', $mid['bgc']);
		}

		//Main background image
		$mid['bgimg'] = $input['bgimg'];
		if(get_option('ng_bgimg') === FALSE)
		{
			add_option('ng_bgimg', $mid['bgimg']);
		}
		else
		{
			update_option('ng_bgimg', $mid['bgimg']);
		}

		//Main background image URL
		$mid['bgimg_url'] = $input['bgimg_url'];
		if(get_option('ng_bgimg_url') === FALSE)
		{
			add_option('ng_bgimg_url', $mid['bgimg_url']);
		}
		else
		{
			update_option('ng_bgimg_url', $mid['bgimg_url']);
		}

		//Body font family
		$mid['font_family'] = $input['font_family'];
		if(get_option('ng_font_family') === FALSE)
		{
			add_option('ng_font_family', $mid['font_family']);
		}
		else
		{
			update_option('ng_font_family', $mid['font_family']);
		}

		//Body font size
		if(is_numeric($input['font_size'])) {
			$mid['font_size'] = $input['font_size'];
			if(get_option('ng_font_size') === FALSE)
			{
				add_option('ng_font_size', $mid['font_size']);
			}
			else
			{
				update_option('ng_font_size', $mid['font_size']);
			}
		}
		else
		{
			$mid['font_size'] = '';
		}

		//Body font color
		$mid['text_color'] = $input['text_color'];
		if(get_option('ng_text_color') === FALSE)
		{
			add_option('ng_text_color', $mid['text_color']);
		}
		else
		{
			update_option('ng_text_color', $mid['text_color']);
		}

		//Body link color
		$mid['link_color'] = $input['link_color'];
		if(get_option('ng_link_color') === FALSE)
		{
			add_option('ng_link_color', $mid['link_color']);
		}
		else
		{
			update_option('ng_link_color', $mid['link_color']);
		}

		//H1 font family
		$mid['h1_font_family'] = $input['h1_font_family'];
		if(get_option('ng_h1_font_family') === FALSE)
		{
			add_option('ng_h1_font_family', $mid['h1_font_family']);
		}
		else
		{
			update_option('ng_h1_font_family', $mid['h1_font_family']);
		}

		//H1 font size
		if(is_numeric($input['h1_font_size'])) {
			$mid['h1_font_size'] = $input['h1_font_size'];
			if(get_option('ng_h1_font_size') === FALSE)
			{
				add_option('ng_h1_font_size', $mid['h1_font_size']);
			}
			else
			{
				update_option('ng_h1_font_size', $mid['h1_font_size']);
			}
		}
		else
		{
			$mid['h1_font_size'] = '';
		}

		//H1 font color
		$mid['h1_text_color'] = $input['h1_text_color'];
		if(get_option('ng_h1_text_color') === FALSE)
		{
			add_option('ng_h1_text_color', $mid['h1_text_color']);
		}
		else
		{
			update_option('ng_h1_text_color', $mid['h1_text_color']);
		}

		//H1 link color
		$mid['h1_link_color'] = $input['h1_link_color'];
		if(get_option('ng_h1_link_color') === FALSE)
		{
			add_option('ng_h1_link_color', $mid['h1_link_color']);
		}
		else
		{
			update_option('ng_h1_link_color', $mid['h1_link_color']);
		}

		//H2 font family
		$mid['h2_font_family'] = $input['h2_font_family'];
		if(get_option('ng_h2_font_family') === FALSE)
		{
			add_option('ng_h2_font_family', $mid['h2_font_family']);
		}
		else
		{
			update_option('ng_h2_font_family', $mid['h2_font_family']);
		}

		//H2 font size
		if(is_numeric($input['h2_font_size'])) {
			$mid['h2_font_size'] = $input['h2_font_size'];
			if(get_option('ng_h2_font_size') === FALSE)
			{
				add_option('ng_h2_font_size', $mid['h2_font_size']);
			}
			else
			{
				update_option('ng_h2_font_size', $mid['h2_font_size']);
			}
		}
		else
		{
			$mid['h2_font_size'] = '';
		}

		//H2 font color
		$mid['h2_text_color'] = $input['h2_text_color'];
		if(get_option('ng_h2_text_color') === FALSE)
		{
			add_option('ng_h2_text_color', $mid['h2_text_color']);
		}
		else
		{
			update_option('ng_h2_text_color', $mid['h2_text_color']);
		}

		//H2 link color
		$mid['h2_link_color'] = $input['h2_link_color'];
		if(get_option('ng_h2_link_color') === FALSE)
		{
			add_option('ng_h2_link_color', $mid['h2_link_color']);
		}
		else
		{
			update_option('ng_h2_link_color', $mid['h2_link_color']);
		}

		//H3 font family
		$mid['h3_font_family'] = $input['h3_font_family'];
		if(get_option('ng_h3_font_family') === FALSE)
		{
			add_option('ng_h3_font_family', $mid['h3_font_family']);
		}
		else
		{
			update_option('ng_h3_font_family', $mid['h3_font_family']);
		}

		//H3 font size
		if(is_numeric($input['h3_font_size'])) {
			$mid['h3_font_size'] = $input['h3_font_size'];
			if(get_option('ng_h3_font_size') === FALSE)
			{
				add_option('ng_h3_font_size', $mid['h3_font_size']);
			}
			else
			{
				update_option('ng_h3_font_size', $mid['h3_font_size']);
			}
		}
		else
		{
			$mid['h3_font_size'] = '';
		}

		//H3 font color
		$mid['h3_text_color'] = $input['h3_text_color'];
		if(get_option('ng_h3_text_color') === FALSE)
		{
			add_option('ng_h3_text_color', $mid['h3_text_color']);
		}
		else
		{
			update_option('ng_h3_text_color', $mid['h3_text_color']);
		}

		//H3 link color
		$mid['h3_link_color'] = $input['h3_link_color'];
		if(get_option('ng_h3_link_color') === FALSE)
		{
			add_option('ng_h3_link_color', $mid['h3_link_color']);
		}
		else
		{
			update_option('ng_h3_link_color', $mid['h3_link_color']);
		}

		//Header Type
		$mid['header_type'] = $input['header_type'];
		if(get_option('ng_header_type') === FALSE)
		{
			add_option('ng_header_type', $mid['header_type']);
		}
		else
		{
			update_option('ng_header_type', $mid['header_type']);
		}

		//Header image
		$mid['header_img'] = $input['header_img'];
		if(get_option('ng_header_img') === FALSE)
		{
			add_option('ng_header_img', $mid['header_img']);
		}
		else
		{
			update_option('ng_header_img', $mid['header_img']);
		}

		//Header url
		$mid['header_img_url'] = $input['header_img_url'];
		if(get_option('ng_header_img_url') === FALSE)
		{
			add_option('ng_header_img_url', $mid['header_img_url']);
		}
		else
		{
			update_option('ng_header_img_url', $mid['header_img_url']);
		}

		//Header html
		$mid['header_html'] = $input['header_html'];
		if(get_option('ng_header_html') === FALSE)
		{
			add_option('ng_header_html', $mid['header_html']);
		}
		else
		{
			update_option('ng_header_html', $mid['header_html']);
		}

		//Footer Type
		$mid['footer_type'] = $input['footer_type'];
		if(get_option('ng_footer_type') === FALSE)
		{
			add_option('ng_footer_type', $mid['footer_type']);
		}
		else
		{
			update_option('ng_footer_type', $mid['footer_type']);
		}

		//Footer image
		$mid['footer_img'] = $input['footer_img'];
		if(get_option('ng_footer_img') === FALSE)
		{
			add_option('ng_footer_img', $mid['footer_img']);
		}
		else
		{
			update_option('ng_footer_img', $mid['footer_img']);
		}

		//Footer url
		$mid['footer_img_url'] = $input['footer_img_url'];
		if(get_option('ng_footer_img_url') === FALSE)
		{
			add_option('ng_footer_img_url', $mid['footer_img_url']);
		}
		else
		{
			update_option('ng_footer_img_url', $mid['footer_img_url']);
		}

		//Footer HTML
		$mid['footer_html'] = $input['footer_html'];
		if(get_option('ng_footer_html') === FALSE)
		{
			add_option('ng_footer_html', $mid['footer_html']);
		}
		else
		{
			update_option('ng_footer_html', $mid['footer_html']);
		}

		// <head> HTML
		$mid['head_html'] = $input['head_html'];
		if(get_option('ng_head_html') === FALSE)
		{
			add_option('ng_head_html', $mid['head_html']);
		}
		else
		{
			update_option('ng_head_html', $mid['head_html']);
		}

		// <body> upper
		$mid['body_up_html'] = $input['body_up_html'];
		if(get_option('ng_body_up_html') === FALSE)
		{
			add_option('ng_body_up_html', $mid['body_up_html']);
		}
		else
		{
			update_option('ng_body_up_html', $mid['body_up_html']);
		}

		// <body> lower HTML
		$mid['body_low_html'] = $input['body_low_html'];
		if(get_option('ng_body_low_html') === FALSE)
		{
			add_option('ng_body_low_html', $mid['body_low_html']);
		}
		else
		{
			update_option('ng_body_low_html', $mid['body_low_html']);
		}

		// Left sidebar HTML
		$mid['left_s_html'] = $input['left_s_html'];
		if(get_option('ng_left_s_html') === FALSE)
		{
			add_option('ng_left_s_html', $mid['left_s_html']);
		}
		else
		{
			update_option('ng_left_s_html', $mid['left_s_html']);
		}

		// Right sidebar HTML
		$mid['right_s_html'] = $input['right_s_html'];
		if(get_option('ng_right_s_html') === FALSE)
		{
			add_option('ng_right_s_html', $mid['right_s_html']);
		}
		else
		{
			update_option('ng_right_s_html', $mid['right_s_html']);
		}

		// Padding between posts
		if(is_numeric($input['posts_padding'])) {
			$mid['posts_padding'] = $input['posts_padding'];
			if(get_option('ng_posts_padding') === FALSE)
			{
				add_option('ng_posts_padding', $mid['posts_padding']);
			}
			else
			{
				update_option('ng_posts_padding', $mid['posts_padding']);
			}
		}
		else
		{
			$mid['posts_padding'] = '';
		}

		// Thumbnail width
		if(is_numeric($input['thumb_width'])) {
			$mid['thumb_width'] = $input['thumb_width'];
			if(get_option('ng_thumb_width') === FALSE)
			{
				add_option('ng_thumb_width', $mid['thumb_width']);
			}
			else
			{
				update_option('ng_thumb_width', $mid['thumb_width']);
			}
		}
		else
		{
			$mid['thumb_width'] = '';
		}

		// Thumbnail alignment
		$mid['thumb_align'] = $input['thumb_align'];
		if(get_option('ng_thumb_align') === FALSE)
		{
			add_option('ng_thumb_align', $mid['thumb_align']);
		}
		else
		{
			update_option('ng_thumb_align', $mid['thumb_align']);
		}

		// Thumbnail padding
		if(is_numeric($input['thumb_pad'])) {
			$mid['thumb_pad'] = $input['thumb_pad'];
			if(get_option('ng_thumb_pad') === FALSE)
			{
				add_option('ng_thumb_pad', $mid['thumb_pad']);
			}
			else
			{
				update_option('ng_thumb_pad', $mid['thumb_pad']);
			}
		}
		else
		{
			$mid['thumb_pad'] = '';
		}

		// Post Font
		$mid['posts_font'] = $input['posts_font'];
		if(get_option('ng_posts_font') === FALSE)
		{
			add_option('ng_posts_font', $mid['posts_font']);
		}
		else
		{
			update_option('ng_posts_font', $mid['posts_font']);
		}

		// Title text size
		if(is_numeric($input['title_size'])) {
			$mid['title_size'] = $input['title_size'];
			if(get_option('ng_title_size') === FALSE)
			{
				add_option('ng_title_size', $mid['title_size']);
			}
			else
			{
				update_option('ng_title_size', $mid['title_size']);
			}
		}
		else
		{
			$mid['title_size'] = '';
		}

		// Title text color
		$mid['title_color'] = $input['title_color'];
		if(get_option('ng_title_color') === FALSE)
		{
			add_option('ng_title_color', $mid['title_color']);
		}
		else
		{
			update_option('ng_title_color', $mid['title_color']);
		}

		// Date text size
		if(is_numeric($input['date_size'])) {
			$mid['date_size'] = $input['date_size'];
			if(get_option('ng_date_size') === FALSE)
			{
				add_option('ng_date_size', $mid['date_size']);
			}
			else
			{
				update_option('ng_date_size', $mid['date_size']);
			}
		}
		else
		{
			$mid['date_size'] = '';
		}

		// Date text color
		$mid['date_color'] = $input['date_color'];
		if(get_option('ng_date_color') === FALSE)
		{
			add_option('ng_date_color', $mid['date_color']);
		}
		else
		{
			update_option('ng_date_color', $mid['date_color']);
		}


		// Author text size
		if(is_numeric($input['author_size'])) {
			$mid['author_size'] = $input['author_size'];
			if(get_option('ng_author_size') === FALSE)
			{
				add_option('ng_author_size', $mid['author_size']);
			}
			else
			{
				update_option('ng_author_size', $mid['author_size']);
			}
		}
		else
		{
			$mid['author_size'] = '';
		}

		// Author text color
		$mid['author_color'] = $input['author_color'];
		if(get_option('ng_author_color') === FALSE)
		{
			add_option('ng_author_color', $mid['author_color']);
		}
		else
		{
			update_option('ng_author_color', $mid['author_color']);
		}



		// Post text size
		if(is_numeric($input['post_size'])) {
		$mid['post_size'] = $input['post_size'];
		if(get_option('ng_post_size') === FALSE)
		{
			add_option('ng_post_size', $mid['post_size']);
		}
		else
		{
			update_option('ng_post_size', $mid['post_size']);
		}
		}
		else
		{
			$mid['post_size'] = '';
		}
		// Post text color
		$mid['post_color'] = $input['post_color'];
		if(get_option('ng_post_color') === FALSE)
		{
			add_option('ng_post_color', $mid['post_color']);
		}
		else
		{
			update_option('ng_post_color', $mid['post_color']);
		}

		// Post text link color
		$mid['post_link_color'] = $input['post_link_color'];
		if(get_option('ng_post_link_color') === FALSE)
		{
			add_option('ng_post_link_color', $mid['post_link_color']);
		}
		else
		{
			update_option('ng_post_link_color', $mid['post_link_color']);
		}

		// Read more link text
		$mid['rm_text'] = $input['rm_text'];
		if(get_option('ng_rm_text') === FALSE)
		{
			add_option('ng_rm_text', $mid['rm_text']);
		}
		else
		{
			update_option('ng_rm_text', $mid['rm_text']);
		}

		// Read more link size
		if(is_numeric($input['rm_size'])) {
			$mid['rm_size'] = $input['rm_size'];
			if(get_option('ng_rm_size') === FALSE)
			{
				add_option('ng_rm_size', $mid['rm_size']);
			}
			else
			{
				update_option('ng_rm_size', $mid['rm_size']);
			}
		}
		else
		{
			$mid['rm_size'] = '';
		}

		// Read more link color
		$mid['rm_color'] = $input['rm_color'];
		if(get_option('ng_rm_color') === FALSE)
		{
			add_option('ng_rm_color', $mid['rm_color']);
		}
		else
		{
			update_option('ng_rm_color', $mid['rm_color']);
		}

		// Post Layout HTML field
		$mid['layout_html'] = $input['layout_html'];
		if(get_option('ng_layout_html') === FALSE)
		{
			add_option('ng_layout_html', $mid['layout_html']);
		}
		else
		{
			update_option('ng_layout_html', $mid['layout_html']);
		}

		add_settings_error(
		'settings-error',
		'settings_updated',
		$message,
		$type
		);

		return $mid;
	}

	//*
	//* General Section
	public function settings_section_info()
	{
		?>
<p>
	Adjust the settings for your newsletter content here. These settings
	control the header, sidebars (optional) footer, and the posts. <br />
	Some knowledge of HTML may be required for advanced layouts. Any URLs
	must be absolute (include http://). <br /> After updating the settings,
	you can use the “Preview” button to view your layout.
</p>
<?php 
	}

	//Left sidebar width
	public function create_ls_width_field(){
    	?>
<input type="text" id="ls_width_field" name="keys[ls_width]" value="<?=get_option('ng_ls_width');?>" />(leave blank for no left sidebar)
<?php
    }

    //Content width
    public function create_content_width_field(){
    	if(get_option('ng_content_width') === FALSE)
    	{
    		add_option('ng_content_width', '600');
    	}
    	?>
<input type="text" id="content_width_field" name="keys[content_width]" value="<?=get_option('ng_content_width');?>" />
<?php
    }

    //Right sidebar width
    public function create_rs_width_field(){
    	?>
<input type="text" id="rs_width_field" name="keys[rs_width]" value="<?=get_option('ng_rs_width');?>" />(leave blank for no right sidebar)
<?php
    }

    //Padding between main blocks
    public function create_pbmb_field(){
    	if(get_option('ng_pbmb') === FALSE)
    	{
    		add_option('ng_pbmb', '10');
    	}
    	?>
<input type="text"	id="pbmb_field" name="keys[pbmb]" value="<?=get_option('ng_pbmb');?>" />
<?php
    }

    //Main background color or image
    public function create_mbg_field(){
    	if(get_option('ng_mbg') === FALSE)
    	{
    		add_option('ng_mbg', '0');
    	}
    	?>
<input type="radio"
	class="image" id="mbg_field" name="keys[mbg]" value="0"	<? checked('0',get_option('ng_mbg'));?> />Color
<input type="radio"	class="image" id="mbg_field1" name="keys[mbg]" value="1" <? checked('1',get_option('ng_mbg'));?> />Image
<?php
	}

	//Main background color
	public function create_bgc_field(){
		if(get_option('ng_bgc') === FALSE)
		{
			add_option('ng_bgc', '#fff');
		}
		?>
<input type="text" id="bgc_field" name="keys[bgc]" value="<?=get_option('ng_bgc');?>" />
<?php
	}

	//Main background image
	public function create_bgimg_field(){
    	?>
<input class="button upload" name="bgimg_button" id="bgimg_button" value="Upload" />
<?php
    }
     
    //Main background image URL
    public function create_bgimg_url_field(){
       	?>
<input type="text"
	name="keys[bgimg_url]" id="bgimg" value="<?=get_option('ng_bgimg_url');?>" />
<?php
    }

    // Content Settings Section
    public function content_settings_info()
    {
    	?>
<p>The following styles are used for all content EXCEPT for posts which	appears in a separate section below:</p>
<?php 
    }

    //*
    //* Content styles

    //Body font family
    public function create_font_family_field(){
    	if(get_option('ng_font_family') === FALSE)
    	{
    		add_option('ng_font_family', 'Arial');
    	}
    	?>
<input type="text" id="font_family_field" name="keys[font_family]"	value="<?=get_option('ng_font_family');?>" />
<?php
    }

    //Body font size
    public function create_font_size_field(){
        if(get_option('ng_font_size') === FALSE)
        {
        	add_option('ng_font_size', '10');
        }
        ?>
<input type="text" id="font_size_field" name="keys[font_size]" value="<?=get_option('ng_font_size');?>" />
<?php
    }

    //Body font color
    public function create_text_color_field(){
        if(get_option('ng_text_color') === FALSE)
        {
        	add_option('ng_text_color', '#000');
        }
        ?>
<input type="text" id="text_color_field" name="keys[text_color]" value="<?=get_option('ng_text_color');?>" />
<?php
    }

    //Body link color
    public function create_link_color_field(){
        if(get_option('ng_link_color') === FALSE)
        {
        	add_option('ng_link_color', '#1982D1');
        }
        ?>
<input type="text" id="link_color_field" name="keys[link_color]" value="<?=get_option('ng_link_color');?>" />
<?php
    }

    //H1 font family
    public function create_h1_font_family_field(){
    	if(get_option('ng_h1_font_family') === FALSE)
    	{
    		add_option('ng_h1_font_family', 'Arial');
    	}
    	?>
<input type="text" id="h1_font_family_field" name="keys[h1_font_family]" value="<?=get_option('ng_h1_font_family');?>" />
<?php
    }

    //H1 font size
    public function create_h1_font_size_field(){
        if(get_option('ng_h1_font_size') === FALSE)
        {
        	add_option('ng_h1_font_size', '10');
        }
        ?>
<input type="text" id="h1_font_size_field" name="keys[h1_font_size]" value="<?=get_option('ng_h1_font_size');?>" />
<?php
    }

    //H1 font color
    public function create_h1_text_color_field(){
        if(get_option('ng_h1_text_color') === FALSE)
        {
        	add_option('ng_h1_text_color', '#000');
        }
        ?>
<input type="text" id="h1_text_color_field" name="keys[h1_text_color]" value="<?=get_option('ng_h1_text_color');?>" />
<?php
    }

    //H1 link color
    public function create_h1_link_color_field(){
        if(get_option('ng_h1_link_color') === FALSE)
        {
        	add_option('ng_h1_link_color', '#1982D1');
        }
        ?>
<input type="text" id="h1_link_color_field" name="keys[h1_link_color]" value="<?=get_option('ng_h1_link_color');?>" />
<?php
    }

    //H2 font family
    public function create_h2_font_family_field(){
        if(get_option('ng_h2_font_family') === FALSE)
        {
        	add_option('ng_h2_font_family', 'Arial');
        }
        ?>
<input type="text" id="h2_font_family_field" name="keys[h2_font_family]" value="<?=get_option('ng_h2_font_family');?>" />
<?php
    }

    //H2 font size
    public function create_h2_font_size_field(){
        if(get_option('ng_h2_font_size') === FALSE)
        {
        	add_option('ng_h2_font_size', '10');
        }
        ?>
<input type="text" id="h2_font_size_field" name="keys[h2_font_size]" value="<?=get_option('ng_h2_font_size');?>" />
<?php
    }


    //H2 font color
    public function create_h2_text_color_field(){
        if(get_option('ng_h2_text_color') === FALSE)
        {
        	add_option('ng_h2_text_color', '#000');
        }
        ?>
<input type="text" id="h2_text_color_field" name="keys[h2_text_color]" value="<?=get_option('ng_h2_text_color');?>" />
<?php
    }

    //H2 link color
    public function create_h2_link_color_field(){
        if(get_option('ng_h2_link_color') === FALSE)
        {
        	add_option('ng_h2_link_color', '#1982D1');
        }
        ?>
<input type="text" id="h2_link_color_field" name="keys[h2_link_color]" value="<?=get_option('ng_h2_link_color');?>" />
<?php
    }

    //H3 font family
    public function create_h3_font_family_field(){
    	if(get_option('ng_h3_font_family') === FALSE)
    	{
    		add_option('ng_h3_font_family', 'Arial');
    	}
    	?>
<input type="text" id="h3_font_family_field" name="keys[h3_font_family]" value="<?=get_option('ng_h3_font_family');?>" />
<?php
    }

    //H3 font size
    public function create_h3_font_size_field(){
        if(get_option('ng_h3_font_size') === FALSE)
        {
        	add_option('ng_h3_font_size', '10');
        }
        ?>
<input type="text" id="h3_font_size_field" name="keys[h3_font_size]" value="<?=get_option('ng_h3_font_size');?>" />
<?php
    }

    //H3 font color
    public function create_h3_text_color_field(){
        if(get_option('ng_h3_text_color') === FALSE)
        {
        	add_option('ng_h3_text_color', '#000');
        }
        ?>
<input type="text" id="h3_text_color_field" name="keys[h3_text_color]" value="<?=get_option('ng_h3_text_color');?>" />
<?php
    }

    //H3 link color
    public function create_h3_link_color_field(){
        if(get_option('ng_h3_link_color') === FALSE)
        {
        	add_option('ng_h3_link_color', '#1982D1');
        }
        ?>
<input type="text" id="h3_link_color_field" name="keys[h3_link_color]" value="<?=get_option('ng_h3_link_color');?>" />
<?php
   }

   // Head section info
   public function head_settings_info(){
   	?>
   <p>This section is optional and is used to add additional HTML in between the &lt;head&gt; and &lt;/head&gt; tags</p>
   <?php
      }
   
      // <head> HTML
      public function create_head_html_field(){
      		?>
   <textarea id="head_html_field" name="keys[head_html]"><?=get_option('ng_head_html');?></textarea>
   <?php
      }
   
   //*
   //* Header Section
      
   public function header_settings_info(){
     ?>
   <p>This section is optional and is used to add additional HTML directly beneath the &lt;body&gt; tag</p>
     <?php
      }      
      
   //Header Type
   public function create_header_type_field(){
   	if(get_option('ng_header_type') === FALSE)
   	{
   		add_option('ng_header_type', '0');
   	}
   	?>
<input type="radio"	id="header_type_field" name="keys[header_type]" value="0" <? checked('0',get_option('ng_header_type'));?> />Image
<input type="radio"	id="header_type_field1" name="keys[header_type]" value="1" <? checked('1',get_option('ng_header_type'));?> />HTML
<?php
   }

   //Header image
   public function create_header_img_field(){
		?>
<input class="button upload" name="header_img_button" id="header_img_button" value="Upload" />
<?php

   }

   //Header url
   public function create_header_img_url_field(){
   		?>
<input type="text" name="keys[header_img_url]" id="header_img" value="<?=get_option('ng_header_img_url');?>" />
<?php
   }

   //Header html
   public function create_header_html_field(){
   		?>
<textarea id="header_html_field" name="keys[header_html]"><?=get_option('ng_header_html');?></textarea>
<?php
   }
   //*
   //* HTML Section

   // Body upper section info
   public function body_up_settings_info(){
   	?>
<p>This section is optional and is used to add additional HTML directly above the posts</p>
<?php
   }

   // <body> upper
   public function create_body_up_html_field(){
   		?>
<textarea id="body_up_html_field" name="keys[body_up_html]"><?=get_option('ng_body_up_html');?></textarea>
<?php
   }

   // Body lower section info info
   public function body_low_settings_info(){
   	?>
<p>This section is optional and is used to add additional HTML directly below the posts</p>
<?php
   }

   // <body> lower HTML
   public function create_body_low_html_field(){
    	?>
<textarea id="body_low_html_field" name="keys[body_low_html]"><?=get_option('ng_body_low_html');?></textarea>
<?php
   }

   // Left sidebar HTML info
   public function left_s_settings_info(){
   	?>
<p>This section is only relevant when General Settings &gt; Left sidebar width has been specified</p>
<?php
   }

   // Left sidebar HTML
   public function create_left_s_html_field(){
      	?>
<textarea id="left_s_html_field" name="keys[left_s_html]"><?=get_option('ng_left_s_html');?></textarea>
<?php
   }

   // Right sidebar HTML info
   public function right_s_settings_info(){
   	?>
<p>This section is only relevant when General Settings &gt; Right sidebar width has been specified</p>
<?php
   }

   // Right sidebar HTML
   public function create_right_s_html_field(){
        ?>
<textarea id="right_s_html_field" name="keys[right_s_html]"><?=get_option('ng_right_s_html');?></textarea>
<?php
   }

   //*
   //* Footer Section
   
   public function footer_settings_info(){
   	?>
      <p>This section is optional and is used to add additional HTML directly above the ending &lt;/body&gt; tag</p>
        <?php
         }  
   
   //Footer Type
   public function create_footer_type_field(){
   	if(get_option('ng_footer_type') === FALSE)
   	{
   		add_option('ng_footer_type', '0');
   	}
   	?>
   <input type="radio"	id="footer_type_field" name="keys[footer_type]" value="0" <? checked('0',get_option('ng_footer_type'));?> />Image
   <input type="radio"	id="footer_type_field1" name="keys[footer_type]" value="1" <? checked('1',get_option('ng_footer_type'));?> />HTML
   <?php
      }
   
      //Footer image
      public function create_footer_img_field(){
   	   	?>
   <input class="button upload" name="footer_img_button" id="footer_img_button" value="Upload" />
   <?php
      }
   
      //Footer url
      public function create_footer_img_url_field(){
   	   	?>
   <input type="text" name="keys[footer_img_url]" id="footer_img"	value="<?=get_option('ng_footer_img_url');?>" />
   <?php
      }
   
      //Footer html
      public function create_footer_html_field(){
   	   	?>
   <textarea id="footer_html_field" name="keys[footer_html]"><?=get_option('ng_footer_html');?></textarea>
   <?php
      }
   
   
   //*
   //* Post Settings

   // Post settings info
   public function post_settings_info(){
   	?>
<p>The following settings ONLY apply to posts:</p>
<?php
   }

   // Padding between posts
   public function create_posts_padding_field(){
	   	if(get_option('ng_posts_padding') === FALSE)
	   	{
	   		add_option('ng_posts_padding', '10');
	   	}
	   	?>
<input type="text" id="posts_padding_field" name="keys[posts_padding]" value="<?=get_option('ng_posts_padding');?>" />
<?php
   }

   // Thumbnail width
   public function create_thumb_width_field(){
	   	if(get_option('ng_thumb_width') === FALSE)
	   	{
	   		add_option('ng_thumb_width', '72');
	   	}
	   	?>
<input type="text" id="thumb_width_field" name="keys[thumb_width]" value="<?=get_option('ng_thumb_width');?>" />
<?php
   }

   // Thumbnail alignment
   public function create_thumb_align_field(){
	   	if(get_option('ng_thumb_align') === FALSE)
	   	{
	   		add_option('ng_thumb_align', '0');
	   	}
	   	?>
<input type="radio" id="thumb_align_field" name="keys[thumb_align]" value="0" <? checked('0',get_option('ng_thumb_align'));?> />Left
<input type="radio"	id="thumb_align_field1" name="keys[thumb_align]" value="1" <? checked('1',get_option('ng_thumb_align'));?> />Right
<?php
   }

   // Thumbnail padding
   public function create_thumb_pad_field(){
	   	if(get_option('ng_thumb_pad') === FALSE)
	   	{
	   		add_option('ng_thumb_pad', '10');
	   	}
	   	?>
<input type="text" id="thumb_pad_field" name="keys[thumb_pad]" value="<?=get_option('ng_thumb_pad');?>" />
<?php
   }

   // Post Font
   public function create_posts_font_field(){
	   	if(get_option('ng_posts_font') === FALSE)
	   	{
	   		add_option('ng_posts_font', 'Arial');
	   	}
	   	?>
<input type="text" id="posts_font_field" name="keys[posts_font]" value="<?=get_option('ng_posts_font');?>" />
<?php
   }

   // Title text size
   public function create_title_size_field(){
	   	if(get_option('ng_title_size') === FALSE)
	   	{
	   		add_option('ng_title_size', '14');
	   	}
	   	?>
<input type="text" id="title_size_field" name="keys[title_size]" value="<?=get_option('ng_title_size');?>" />
<?php
   }

   // Title text color
   public function create_title_color_field(){
	   	if(get_option('ng_title_color') === FALSE)
	   	{
	   		add_option('ng_title_color', '#000');
	   	}
	   	?>
<input type="text" id="title_color_field" name="keys[title_color]" value="<?=get_option('ng_title_color');?>" />
<?php
   }

   // Date text size
   public function create_date_size_field(){
	   	if(get_option('ng_date_size') === FALSE)
	   	{
	   		add_option('ng_date_size', '8');
	   	}
	   	?>
<input type="text" id="date_size_field" name="keys[date_size]" value="<?=get_option('ng_date_size');?>" />
<?php
   }

   // Date text color
   public function create_date_color_field(){
	   	if(get_option('ng_date_color') === FALSE)
	   	{
	   		add_option('ng_date_color', '#ccc');
	   	}
	   	?>
<input type="text" id="date_color_field" name="keys[date_color]" value="<?=get_option('ng_date_color');?>" />
<?php
   }

   // Author text size
   public function create_author_size_field(){
	   	if(get_option('ng_author_size') === FALSE)
	   	{
	   		add_option('ng_author_size', '8');
	   	}
	   	?>
<input type="text" id="author_size_field" name="keys[author_size]" value="<?=get_option('ng_author_size');?>" />
<?php
   }

   // Author text color
   public function create_author_color_field(){
	   	if(get_option('ng_author_color') === FALSE)
	   	{
	   		add_option('ng_author_color', '#ccc');
	   	}
	   	?>
<input type="text" id="author_color_field" name="keys[author_color]" value="<?=get_option('ng_author_color');?>" />
<?php
   }

   // Post text font
   public function create_post_font_field(){
	   	if(get_option('ng_post_font') === FALSE)
	   	{
	   		add_option('ng_post_font', 'Arial');
	   	}
	   	?>
<input type="text" id="post_font_field" name="keys[post_font]" value="<?=get_option('ng_post_font');?>" />
<?php
   }

   // Post text size
   public function create_post_size_field(){
	   	if(get_option('ng_post_size') === FALSE)
	   	{
	   		add_option('ng_post_size', '12');
	   	}
	   	?>
<input type="text" id="post_size_field" name="keys[post_size]" value="<?=get_option('ng_post_size');?>" />
<?php
   }

   // Post text color
   public function create_post_color_field(){
	   	if(get_option('ng_post_color') === FALSE)
	   	{
	   		add_option('ng_post_color', '#000');
	   	}
	   	?>
<input type="text" id="post_color_field" name="keys[post_color]" value="<?=get_option('ng_post_color');?>" />
<?php
   }

   // Post text link color
   public function create_post_link_color_field(){
	   	if(get_option('ng_post_link_color') === FALSE)
	   	{
	   		add_option('ng_post_link_color', '#1982D1');
	   	}
	   	?>
<input type="text" id="post_link_color_field" name="keys[post_link_color]" value="<?=get_option('ng_post_link_color');?>" />
<?php
   }

   // Read more link text
   public function create_rm_text_field(){
		if(get_option('ng_rm_text') === FALSE)
		{
			add_option('ng_rm_text', 'read more');
		}   	
	?>
<input type="text" id="rm_text_field" name="keys[rm_text]" value="<?=get_option('ng_rm_text');?>" />
<?php
      }

      // Read more link size
      public function create_rm_size_field(){
	   	if(get_option('ng_rm_size') === FALSE)
	   	{
	   		add_option('ng_rm_size', '10');
	   	}
	   	?>
<input type="text" id="rm_size_field" name="keys[rm_size]" value="<?=get_option('ng_rm_size');?>" />
<?php
   }

   // Read more link color
   public function create_rm_color_field(){
	   	if(get_option('ng_rm_color') === FALSE)
	   	{
	   		add_option('ng_rm_color', '#F41D1D');
	   	}
	   	?>
<input type="text" id="rm_color_field" name="keys[rm_color]" value="<?=get_option('ng_rm_color');?>" />
<?php
   }

   // Post Layout HTML section
   public function post_layout_info(){?>
<p>Use standard HTML tags including H1-H3 and the special tags	&lt;title&gt;, &lt;date&gt;, &lt;author&gt;, &lt;thumbnail&gt;, and	&lt;readmore&gt;</p>
<?php
   }

   // Post Layout HTML field
   public function create_layout_html_field(){
	   	if(get_option('ng_layout_html') === FALSE)
	   	{
	   		add_option('ng_layout_html', '<title><date><author></br><thumbnail> <excerpt><readmore>');
	   	}
	   	?>
<textarea id="layout_html" name="keys[layout_html]"><?=get_option('ng_layout_html');?></textarea>
<?php
   }
    
   // Reset Default Post Layout HTML
   public function create_reset_layout_button(){
   	?>

<script type="text/Javascript">
function changeValue() {
var changer = document.getElementById('layout_html');		  
changer.value = "<title>\n<date><author></br>\n<thumbnail><excerpt>\n<readmore>";
}
</script>
<input type="button" id="reset_layout_button" name="keys[reset_layout]" value="reset" onClick="changeValue()" />
<?php
   }
}
/*
function add_ng_script(){
	wp_enqueue_script( 'newsletter_js', plugins_url('ss-newsletter-generator/common.js'),5);
	wp_enqueue_script( 'jquery_ui', plugins_url('ss-newsletter-generator/jquery-ui.js'),5);
}
add_action( 'admin_enqueue_scripts', 'add_ng_script' );*/
