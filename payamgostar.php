<?php
/*
Plugin Name:ATS پیامگستر
Plugin URI: https://www.payamgostar.com/fa/solutions/by-need/hr-ats
Description: ارتباط فرصت‌های شغلی از crm پیامگستر به وردپرس
Author: تیم پیامگستر
Author URI:https://payamgostar.com
Version: 0.0.1
Requires PHP: 5.5

*/

function payamgostar_register_settings() {
    add_option('payamgostar_username', '');
    add_option('payamgostar_password', '');
    add_option('payamgostar_endpoint', '');
  
    register_setting('payamgostar_settings_group', 'payamgostar_username');
    register_setting('payamgostar_settings_group', 'payamgostar_password');
    register_setting('payamgostar_settings_group', 'payamgostar_endpoint');

    add_option('ats-template-checkbox', '');

    add_option('pg_template_html_code', '');
    register_setting('pg_template_html_code1', 'pg_template_html_code');

}


// Create the admin menu page
function payamgostar_create_admin_page() {
    add_menu_page(
        'payamgostar Settings',
        'ATS پیامگستر',
        'manage_options',
        'payamgostar-settings',
        'payamgostar_settings_page',
        'dashicons-admin-generic',
        20
    );
    add_submenu_page(
        'payamgostar-settings', 
        'ایجاد قالب اختصاصی',
        'ایجاد قالب اختصاصی',
        'manage_options',
        'payamgostar-template', 
        'payamgostar_template_page' );
}

function payamgostar_settings_page() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/settingpage.php';
}
function payamgostar_template_page() {
  require_once plugin_dir_path( __FILE__ ) . 'includes/template.php';
}
function pg_template_save_html_code() {
  if (isset($_POST['pg_template_html_editor'])) {
      $content = $_POST['pg_template_html_editor'];
      update_option('pg_template_html_code', stripslashes($content));
  }
  elseif (isset($_POST['pg_singletemplate_html_editor'])) {
    $content = $_POST['pg_singletemplate_html_editor'];
    update_option('pg_singletemplate_html_code', stripslashes($content));
}
}
function pg_template_handle_form_submission() {
  pg_template_save_html_code();
  wp_redirect(admin_url('admin.php?page=payamgostar-template'));
  exit();
}
add_action('admin_post_save_html_code', 'pg_template_handle_form_submission');

wp_register_script('checkbox-script-handle',plugin_dir_url( __FILE__ ) . 'script/checkbox.js', array('jquery') ); 
$checkbox_value = get_option('ats-template-checkbox');
wp_enqueue_script('checkbox-script-handle');
wp_localize_script('checkbox-script-handle', 'checkbox_script_data', array(
    'ajax_url' => admin_url('admin-ajax.php'),
    'checkbox_value' => $checkbox_value,
));

function save_checkbox_value() {
  if (isset($_POST['checkbox_value'])) {
      $checkbox_value = $_POST['checkbox_value'];
      update_option('ats-template-checkbox', $checkbox_value);
      //echo 'Checkbox value saved successfully.';
  }
  wp_die();
}
add_action('wp_ajax_save_checkbox_value', 'save_checkbox_value');

function stylescripts() {
  wp_enqueue_style( 'mainstyle', plugin_dir_url( __FILE__ ) . 'css/style.css' );

}
function enqueue_admin_styles() {
  wp_enqueue_style('my-admin-styles', plugin_dir_url(__FILE__) . 'css/admin.css');
}
function atsplugin_activate() {
  $upload = wp_upload_dir();
  $upload_dir = $upload['basedir'];
  $upload_dir = $upload_dir . '/ats-resumes';
  if (! is_dir($upload_dir)) {
     mkdir( $upload_dir, 0700 );
  }
}
register_activation_hook( __FILE__, 'atsplugin_activate' );
add_action('admin_init', 'payamgostar_register_settings');
add_action('wp_enqueue_scripts', 'stylescripts');
add_action('admin_menu', 'payamgostar_create_admin_page');
add_action('admin_enqueue_scripts', 'enqueue_admin_styles');
require_once plugin_dir_path( __FILE__ ) . 'includes/showresult.php';

?>

