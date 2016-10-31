<?php
/**
Plugin Name: Protect WP-Admin
Plugin URI: http://www.mrwebsolution.in/
Description: "protect-wp-admin" is a very help full plugin to make wordpress admin more secure. Protect WP-Admin plugin is provide the options for change the wp-admin url and make the login page private(directly user can't access the login page).
Author: MR Web Solution
Author URI: http://www.mrwebsolution.in/
Version: 2.2
*/

/*** Protect WP-Admin Copyright 2014  Raghunath  (email : raghunath.0087@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
***/
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**
 * Initialize "Protect WP-Admin" plugin admin menu 
 * @create new menu
 * @create plugin settings page
 */
add_action('admin_menu','init_pwa_admin_menu');
if(!function_exists('init_pwa_admin_menu')):
function init_pwa_admin_menu(){

	add_options_page('Protect WP-Admin','Protect WP-Admin','manage_options','pwa-settings','init_pwa_admin_option_page');

}
endif;
/** Define Action to register "Protect WP-Admin" Options */
add_action('admin_init','init_pwa_options_fields');
/** Register "Protect WP-Admin" options */
if(!function_exists('init_pwa_options_fields')):
function init_pwa_options_fields(){
	register_setting('pwa_setting_options','pwa_active');
	register_setting('pwa_setting_options','pwa_rewrite_text');	
	register_setting('pwa_setting_options','pwa_restrict');	
	register_setting('pwa_setting_options','pwa_logout');
	register_setting('pwa_setting_options','pwa_allow_custom_users');
	register_setting('pwa_setting_options','pwa_logo_path');
	register_setting('pwa_setting_options','pwa_login_page_bg_color');
} 
endif;
/** Add settings link to plugin list page in admin */
add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), 'pwa_action_links' );
if(!function_exists('pwa_action_links')):
function pwa_action_links( $links ) {
   $links[] = '<a href="'. get_admin_url(null, 'options-general.php?page=pwa-settings') .'">Settings</a> | <a href="https://rgaddons.wordpress.com/wp-importer-pro/">GO PRO</a>';
   return $links;
}
endif;

/** Options Form HTML for "Protect WP-Admin" plugin */
if(!function_exists('init_pwa_admin_option_page')):
function init_pwa_admin_option_page(){ 
		if (get_option('permalink_structure') ){ $permalink_structure_val='yes'; }else{$permalink_structure_val='no';}
	?>
	<div style="width: 80%; padding: 10px; margin: 10px;"> 
	
	<h1>Protect WP-Admin Settings</h1>
  <!-- Start Options Form -->
	<form action="options.php" method="post" id="pwa-settings-form-admin">
	<input type="hidden"  id="check_permalink" value="<?php echo $permalink_structure_val;?>">	
	<div id="pwa-tab-menu"><a id="pwa-general" class="pwa-tab-links active" >General</a> <a  id="pwa-admin-style" class="pwa-tab-links">LoginPage Style</a> <a  id="pwa-advance" class="pwa-tab-links">Advance Settings</a> <a  id="pwa-support" class="pwa-tab-links">Support</a> </div>
	<div class="pwa-setting">
		<!-- General Setting -->	
	<div class="first pwa-tab" id="div-pwa-general">
	<h2>General Settings</h2>
	<p><label>Enable: </label><input type="checkbox" id="pwa_active" name="pwa_active" value='1' <?php if(get_option('pwa_active')!=''){ echo ' checked="checked"'; }?>/></p>
	<p id="adminurl"><label>Admin Slug: </label><input type="text" id="pwa_rewrite_text" name="pwa_rewrite_text" value="<?php echo esc_attr(get_option('pwa_rewrite_text')); ?>"  placeholder="myadmin" size="30">(<i>Add New Secure Admin URL Slug ( i.e myadmin )</i>)</p>
	
	<?php 
$getPwaOptions=get_pwa_setting_options();
if((isset($getPwaOptions['pwa_active']) && '1'==$getPwaOptions['pwa_active']) && (isset($getPwaOptions['pwa_rewrite_text']) && $getPwaOptions['pwa_rewrite_text']!='')){
      echo "<p><strong>Your New Admin URL : </strong><a href='".home_url('/'.$getPwaOptions['pwa_rewrite_text'])."' target='_blank'>".home_url('/'.$getPwaOptions['pwa_rewrite_text'])."</a></p><p><strong><blink><a href='".home_url('/'.$getPwaOptions['pwa_rewrite_text'])."' target='_blank'>CLICK HERE</a></blink></strong> for preivew new admin URL.</p>";

     }
?>
	</div>
	
	<!-- Admin Style -->
	<div class="last author pwa-tab" id="div-pwa-admin-style">
	<h2>Admin Login Page Style Settings</h2>
	<p id="adminurl"><label>Define Logo Path: </label><input type="text" id="pwa_logo_path" name="pwa_logo_path" value="<?php echo esc_attr(get_option('pwa_logo_path')); ?>"  placeholder="Add Custom Logo Image Path" size="30">(<i>Change WordPress Default Login Logo </i>)</p>
	<p id="adminurl"><label>Body Background Color: </label><input type="text" id="pwa_login_page_bg_color" name="pwa_login_page_bg_color" value="<?php echo esc_attr(get_option('pwa_login_page_bg_color')); ?>"  placeholder="#444444" size="30"></p>
	</div>
	
	<!-- Advance Setting -->	
	<div class="pwa-tab" id="div-pwa-advance">
	<h2>Advance Settings</h2>

	<p><input type="checkbox" id="pwa_restrict" name="pwa_restrict" value='1' <?php if(get_option('pwa_restrict')!=''){ echo ' checked="checked"'; }?>/> <label>Restrict registered non-admin users from wp-admin :</label></p>
	<p><input type="checkbox" id="pwa_logout" name="pwa_logout" value='1' <?php if(get_option('pwa_logout')==''){ echo ''; }else{echo 'checked="checked"';}?>/> <label>Logout Admin After Add/Update New Admin URL(Optional) :</label> (This is only for security purpose)</p>
	<p><label>Allow access to non-admin users:</label><input type="text" id="pwa_allow_custom_users" name="pwa_allow_custom_users" value="<?php echo esc_attr(get_option('pwa_allow_custom_users')); ?>"  placeholder="1,2,3"> (<i>Add comma seprated ids</i>)</p>
	
	</div>

	
	<!-- Support -->
	<div class="last author pwa-tab" id="div-pwa-support">
	<h2>Plugin Support</h2>
	<table>
	<tr>
	<td width="30%"><p><a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=ZEMSYQUZRUK6A" target="_blank" style="font-size: 17px; font-weight: bold;"><img src="https://www.paypal.com/en_US/i/btn/btn_donate_LG.gif" title="Donate for this plugin"></a></p>
	
	<p><strong>Plugin Author:</strong><br><img src="<?php echo  plugins_url( 'images/raghu.jpg' , __FILE__ );?>" width="75" height="75"><br><a href="http://raghunathgurjar.wordpress.com" target="_blank">Raghunath Gurjar</a></p>
	<p><a href="mailto:raghunath.0087@gmail.com" target="_blank" class="contact-author">Contact Author</a></p>
</td>
	<td>		<p><strong>My Other Plugins:</strong><br>
	<ol>
		<li><a href="https://wordpress.org/plugins/custom-share-buttons-with-floating-sidebar" target="_blank">Custom Share Buttons with Floating Sidebar</a></li>
		<li><a href="https://wordpress.org/plugins/wp-testimonial" target="_blank">WP Testimonial</a></li>
		<li><a href="https://wordpress.org/plugins/wp-easy-recipe/" target="_blank">WP Easy Recipe</a></li>
		<li><a href="https://wordpress.org/plugins/wp-social-buttons/" target="_blank">WP Social Buttons</a></li>
		<li><a href="https://wordpress.org/plugins/wp-youtube-gallery/" target="_blank">WP Youtube Gallery</a></li>
		<li><a href="https://wordpress.org/plugins/cf7-advance-security/" target="_blank">CF7 Advance Security</a></li>
		<li><a href="https://wordpress.org/plugins/tweets-slider/" target="_blank">Tweets Slider</a></li>
		<li><a href="https://wordpress.org/plugins/wc-sales-count-manager/" target="_blank">WooCommerce Sales Count Manager</a></li>
		<li><a href="https://wordpress.org/plugins/rg-responsive-gallery/" target="_blank">RG Responsive Gallery/</a></li>
		<li><a href="https://wordpress.org/plugins/protect-wp-admin/" target="_blank">Protect WP Admin</a></li>
		</ol></p></td>
	</tr>
	</table>

	</div>

	</div>
	<span class="submit-btn"><?php echo get_submit_button('Save Settings','button-primary','submit','','');?></span>
		
		<p ><strong style="color:red;" >Important!:</strong> Don't forget to preview new admin url after update new admin slug.</p>	

    <?php settings_fields('pwa_setting_options'); ?>
	</form>

<!-- End Options Form -->
	</div>

<?php
}
endif;
/** add js into admin footer */
// better use get_current_screen(); or the global $current_screen
if (isset($_GET['page']) && $_GET['page'] == 'pwa-settings') {
   add_action('admin_footer','init_pwa_admin_scripts');
}
if(!function_exists('init_pwa_admin_scripts')):
function init_pwa_admin_scripts()
{
wp_register_style( 'pwa_admin_style', plugins_url( 'css/pwa-admin-min.css',__FILE__ ) );
wp_enqueue_style( 'pwa_admin_style' );

/* check .htaccess file writeable or not*/
$csbwfsHtaccessfilePath = getcwd()."/.htaccess";
$csbwfsHtaccessfilePath = str_replace('/wp-admin/','/',$csbwfsHtaccessfilePath);

if(file_exists($csbwfsHtaccessfilePath)){
	if(is_writable($csbwfsHtaccessfilePath))
	  { $htaccessWriteable="1";}
	  else 
	   { $htaccessWriteable="0";}
}else
{
	$htaccessWriteable="0";
	}
$localHostIP=$_SERVER['REMOTE_ADDR'];
$pwaActive=get_option('pwa_active');
//$pwaNewSlug=get_option('pwa_rewrite_text');
//print_r($_SERVER); exit;
echo $script='<script type="text/javascript">
	/* Protect WP-Admin js for admin */
	jQuery(document).ready(function(){
		jQuery(".pwa-tab").hide();
		jQuery("#div-pwa-general").show();
	    jQuery(".pwa-tab-links").click(function(){
		var divid=jQuery(this).attr("id");
		jQuery(".pwa-tab-links").removeClass("active");
		jQuery(".pwa-tab").hide();
		jQuery("#"+divid).addClass("active");
		jQuery("#div-"+divid).fadeIn();
		});
		   
	   jQuery("#pwa-settings-form-admin .button-primary").click(function(){
		 var $el = jQuery("#pwa_active");
		 var $vlue = jQuery("#pwa_rewrite_text").val();
		 var pwaActive ="'.$pwaActive.'";
		 if((!$el[0].checked) && $vlue=="")
		 {
			 	 alert("Please enable plugin");
			 	 return false;
			 }
			 
		 if(($el[0].checked) && $vlue=="")
		 {
			 	 jQuery("#pwa_rewrite_text").css("border","1px solid red");
			 	 jQuery("#adminurl").append(" <strong style=\'color:red;\'>Please enter admin url slug</strong>");
			 	 return false;
			 }
			
			if(($el[0].checked) && pwaActive==""){
				//alert(pwaActive);
	if (confirm("1. Have you updated your permalink settings?\n\n2. Have you checked writable permission on htaccess file?\n\nIf your answer is YES then Click OK to continue")){
          return true;
      }else
      {
		  return false;
		  }
		 }
			var seoUrlVal=jQuery("#check_permalink").val();
			var htaccessWriteable ="'.$htaccessWriteable.'";
			var hostIP ="'.$localHostIP.'";
		//	alert(hostIP);
			if(seoUrlVal=="no")
			{
			alert("Please update permalinks before activate the plugin. permalinks option should not be default!.");
			document.location.href="'.admin_url('options-permalink.php').'";
			return false;
				}
				/*else if(htaccessWriteable=="0" && hostIP!="127.0.0.1"){
					alert("Error : .htaccess file is not exist OR may be htaccess file is not writable, So please double check it before enable the plugin");
					return false;
					}*/
				else
				{
					return true;
					}
			});
	
		})
	</script>';

}
endif;

// Add Check if permalinks are set on plugin activation
register_activation_hook( __FILE__, 'is_permalink_activate' );
if(!function_exists('is_permalink_activate')):
function is_permalink_activate() {
    //add notice if user needs to enable permalinks
    if (! get_option('permalink_structure') )
        add_action('admin_notices', 'permalink_structure_admin_notice');
}
endif;
if(!function_exists('permalink_structure_admin_notice')):
function permalink_structure_admin_notice(){
    echo '<div id="message" class="error"><p>Please Make sure to enable <a href="options-permalink.php">Permalinks</a>.</p></div>';
}
endif;
/** register_install_hook */
if( function_exists('register_install_hook') ){
register_uninstall_hook(__FILE__,'init_install_pwa_plugins'); 
}
//flush the rewrite
if(!function_exists('init_install_pwa_plugins')):
function init_install_pwa_plugins(){
	  flush_rewrite_rules();
}
endif; 
/** register_uninstall_hook */
/** Delete exits options during disable the plugins */
if( function_exists('register_uninstall_hook') ){
   register_uninstall_hook(__FILE__,'flush_rewrite_rules');
   register_uninstall_hook(__FILE__,'init_uninstall_pwa_plugins');   
}

//Delete all options after uninstall the plugin
if(!function_exists('init_uninstall_pwa_plugins')):
function init_uninstall_pwa_plugins(){
	delete_option('pwa_active');
	delete_option('pwa_rewrite_text');	
	delete_option('pwa_restrict');	
	delete_option('pwa_logout');
	delete_option('pwa_allow_custom_users');
	delete_option('pwa_logo_path');
	delete_option('pwa_login_page_bg_color');
}
endif;
require dirname(__FILE__).'/pwa-class.php';

/** register_deactivation_hook */
/** Delete exits options during deactivation the plugins */
if( function_exists('register_deactivation_hook') ){
   register_deactivation_hook(__FILE__,'init_deactivation_pwa_plugins');  
}

//Delete all options after uninstall the plugin
if(!function_exists('init_deactivation_pwa_plugins')):
function init_deactivation_pwa_plugins(){
	delete_option('pwa_active');
	delete_option('pwa_logout');
	remove_action('init', 'init_pwa_admin_rewrite_rules' );
	flush_rewrite_rules();
}
endif;
/** register_activation_hook */
/** Delete exits options during disable the plugins */
if( function_exists('register_activation_hook') ){
   register_activation_hook(__FILE__,'init_activation_pwa_plugins');    
}
//Delete all options after uninstall the plugin
if(!function_exists('init_activation_pwa_plugins')):
function init_activation_pwa_plugins(){
	delete_option('pwa_logout');
   	flush_rewrite_rules();
}
endif;
?>
