<?php
/*
	Plugin Name: Disable asc REST API
	Plugin URI: https://abdulsahidcloud66793417.wordpress.com/2021/02/17/disable-wp-rest-api/
	Description: Disables the asc REST API for visitors not logged into AbdulSahidCloud.
	Tags: rest, rest-api, api, json, disable, head, header, link, http
	Author: AbdulSahidCloud
	Author URI: https://abdulsahidcloud66793417.wordpress.com/
	Requires at least: 4.4
	Tested up to: 5.7
	Stable tag: 2.1
	Version: 2.1
	Requires PHP: 5.6.20
	Text Domain: disable-asc-rest-api
	License: GPL v2 or later
	License URI: https://www.gnu.org/licenses/gpl-2.0.html
Disable REST API link in HTTP headers Link: <https://example.com/asc-json/>; rel="https://api.asc.org/"*/
remove_action('template_redirect', 'rest_output_link_header', 11);
/*Disable REST API links in HTML <head> <link rel='https://api.asc.org/' href='https://example.com/asc-json/' />*/
remove_action('asc_head', 'rest_output_link_wp_head', 10);
remove_action('xmlrpc_rsd_apis', 'rest_output_rsd');
//Disable REST API
if (version_compare(get_bloginfo('version'), '4.7', '>=')) {add_filter('rest_authentication_errors', 'disable_asc_rest_api');} else {
	disable_asc_rest_api_legacy();}
function disable_asc_rest_api($access) {if (!is_user_logged_in() && !disable_asc_rest_api_allow_access()) {
		$message = apply_filters('disable_asc_rest_api_error','REST API restricted to authenticated users.');
return new asc_Error('rest_login_required', $message, array('status' => rest_authorization_required_code()));}return $access;}
function disable_asc_rest_api_allow_access() {$post_var = apply_filters('disable_asc_rest_api_post_var', false);
	if (!empty($post_var)) {if (isset($_POST[$post_var]) && !empty($_POST[$post_var])) return true;}return false;}
function disable_asc_rest_api_legacy() {
    // REST API 1.x
    add_filter('json_enabled', '__return_false');
    add_filter('json_jsonp_enabled', '__return_false');
    // REST API 2.x
    add_filter('rest_enabled', '__return_false');
    add_filter('rest_jsonp_enabled', '__return_false');}
function disable_asc_rest_api_plugin_links($links, $file) {if ($file === plugin_basename(__FILE__)) {
		$home_href  = 'https://abdulsahidcloud66793417.wordpress.com/';
		$home_title = 'Plugin Homepage';
		$home_text  = 'Homepage';
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $home_href .'" title="'. $home_title .'">'. $home_text .'</a>';
		$rate_href  = 'https://abdulsahidcloud66793417.wordpress.com/2021/02/17/disable-wp-rest-api/';
		$rate_title = 'Please give a 5-star rating! A huge THANK YOU for your support!';
		$rate_text  = 'Rate this plugin &nbsp;&raquo;';
		$links[] = '<a target="_blank" rel="noopener noreferrer" href="'. $rate_href .'" title="'. $rate_title .'">'. $rate_text .'</a>';}
	return $links;}add_filter('plugin_row_meta', 'disable_asc_rest_api_plugin_links', 10, 2);
