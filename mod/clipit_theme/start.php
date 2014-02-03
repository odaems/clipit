<?php

/**
 * Project Name:            ClipIt Theme
 * Project Description:     Theme for Elgg 1.8
 * 
 * PHP version >= 5.2 
 * 
 * Creation date:   2013-06-19
 * 
 * @category    theme
 * @package     clipit
 * @license    GNU Affero General Public License v3
 * http://www.gnu.org/licenses/agpl-3.0.txt
 * 
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, version 3. *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details. *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see
 * http://www.gnu.org/licenses/agpl-3.0.txt.
 */
elgg_register_event_handler('init', 'system', 'clipit_final_init');

function clipit_final_init() {
    global $CONFIG;

	/*
     * Set Dashboard tab on default site tab-bar
     */
    elgg_register_menu_item(
        'site',
        array(
            'name' => 'dashboard',
            'href' => 'dashboard',
            'text' => elgg_echo('dashboard'),
            'priority' => 450,
            'section' => 'default',
        )
    );
    /**
     * Register menu footer
    */
    // Clipit section
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'about',
            'href' => 'about',
            'text' => elgg_echo('about'),
            'priority' => 450,
            'section' => 'clipit',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'team',
            'href' => 'team',
            'text' => elgg_echo('team'),
            'priority' => 455,
            'section' => 'clipit',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'developers',
            'href' => 'developers',
            'text' => elgg_echo('developers'),
            'priority' => 460,
            'section' => 'clipit',
        )
    );
    // Legal section
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'terms',
            'href' => 'terms',
            'text' => elgg_echo('terms'),
            'priority' => 460,
            'section' => 'legal',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'privacy',
            'href' => 'privacy',
            'text' => elgg_echo('privacy'),
            'priority' => 465,
            'section' => 'legal',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'community_guidelines',
            'href' => 'community_guidelines',
            'text' => elgg_echo('community_guidelines'),
            'priority' => 470,
            'section' => 'legal',
        )
    );
    // Help section
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'support_center',
            'href' => 'support_center',
            'text' => elgg_echo('support_center'),
            'priority' => 470,
            'section' => 'help',
        )
    );
    elgg_register_menu_item(
        'footer_clipit',
        array(
            'name' => 'basics',
            'href' => 'basics',
            'text' => elgg_echo('basics'),
            'priority' => 475,
            'section' => 'help',
        )
    );


    elgg_register_admin_menu_item('administer', 'clipit_theme', 'clipit_plugins');
    elgg_register_action("clipit_theme/settings", elgg_get_plugins_path() . "clipit_theme/actions/settings.php", 'admin');
    elgg_register_action('login', elgg_get_plugins_path() . "clipit_theme/actions/login.php", 'public');
    elgg_register_action('logout');
    elgg_register_action('register', elgg_get_plugins_path() . "clipit_theme/actions/register.php", 'public');
    elgg_register_action('user/requestnewpassword', elgg_get_plugins_path() . "clipit_theme/actions/user/requestnewpassword.php", 'public');
    elgg_register_action('user/passwordreset', elgg_get_plugins_path() . "clipit_theme/actions/user/passwordreset.php", 'public');
    elgg_register_action("user/check", elgg_get_plugins_path() . "clipit_theme/actions/check.php", 'public');
    // Registrar páginas publicas
    elgg_register_plugin_hook_handler('public_pages', 'walled_garden', 'actions_clipit_public_pages');
    function actions_clipit_public_pages($hook, $type, $return_value, $params) {
        $return_value[] = 'action/user/check';
        return $return_value;
    }

    elgg_register_page_handler('forgotpassword', 'home_user_account_page_handler');
    elgg_register_page_handler('resetpassword', 'home_user_account_page_handler');
    elgg_register_page_handler('register', 'home_user_account_page_handler');
    elgg_register_page_handler('login', 'home_user_account_page_handler');


    if (elgg_get_context() === "admin") {
        elgg_unregister_css("twitter-bootstrap");
        elgg_unregister_css("ui-lightness");
        elgg_unregister_css("clipit");
        elgg_unregister_css("bubblegum");
        elgg_unregister_css("righteous");
        elgg_unregister_css("ubuntu");
        elgg_unregister_js("jquery-migrate");
        elgg_unregister_js("twitter-bootstrap");
    } else {
        elgg_register_css("twitter-bootstrap", $CONFIG->url . "mod/clipit/vendors/bootstrap/css/bootstrap.css");
        elgg_register_css("ui-lightness", $CONFIG->url . "mod/clipit/vendors/jquery-ui-1.10.2.custom/css/ui-lightness/jquery-ui-1.10.2.custom.min.css");
        elgg_register_css("clipit", $CONFIG->url . "mod/clipit/css/clipit.css");
        elgg_register_css("bubblegum", "http://fonts.googleapis.com/css?family=Bubblegum+Sans");
        elgg_register_css("righteous", "http://fonts.googleapis.com/css?family=Righteous");
        elgg_register_css("ubuntu", "http://fonts.googleapis.com/css?family=Ubuntu:400,300,300italic,400italic,500,500italic,700,700italic");
        elgg_register_js("clipit", $CONFIG->url . "mod/clipit/js/clipit.js");
        elgg_register_js("jquery", $CONFIG->url . "mod/clipit/vendors/jquery/jquery-1.9.1.min.js", "head", 0);
        elgg_register_js("jquery-migrate", $CONFIG->url . "mod/clipit/vendors/jquery/jquery-migrate-1.1.1.js", "head", 1);
        elgg_register_js("jquery-ui", $CONFIG->url . "mod/clipit/vendors/jquery-ui-1.10.2.custom/js/jquery-ui-1.10.2.custom.min.js", "head", 2);
        elgg_load_css("ui-lightness");
        elgg_load_css("twitter-bootstrap");
        elgg_load_js("jquery-migrate");
        elgg_unregister_js("twitter-bootstrap");
        elgg_unregister_js("forceAddFile");
        elgg_unregister_css("righteous");
        elgg_load_css("ubuntu");
        elgg_load_css("bubblegum");
        elgg_unregister_css("twitter-bootstrap");
        elgg_unregister_css("clipit");
        ///////////////////////////////////////
        elgg_register_js("less_dev", $CONFIG->url . "mod/clipit_theme/bootstrap/js/less_dev.js");
        elgg_load_js("less_dev");
        elgg_register_js("clipit_theme_less", $CONFIG->url . "mod/clipit_theme/bootstrap/js/less.js");
        elgg_load_js("clipit_theme_less");
        elgg_register_js("clipit_theme_bootstrap", $CONFIG->url . "mod/clipit_theme/bootstrap/dist/js/bootstrap.js");
        elgg_load_js("clipit_theme_bootstrap");
    }
/*<?php echo '
    <link rel="stylesheet/less" href="'.$CONFIG->url.'mod/clipit_home/bootstrap/less/theme_clipit.less" />
    <script>var less=less||{};less.env=\'development\';</script>
    <script src="'.$CONFIG->url.'mod/clipit_home/bootstrap/js/less.js" ></script>
    <script src="'.$CONFIG->url.'mod/clipit_home/bootstrap/dist/js/bootstrap.js" ></script>
    ';
?>*/
    
}
//elgg_register_plugin_hook_handler('index', 'system', 'elgg_walled_garden_index_clipit', 1);
/*if (!elgg_is_logged_in()) {
    // hook into the index system call at the highest priority
    elgg_trigger_plugin_hook('index', 'system', 'elgg_walled_garden_index_clipit', 1);
}
function elgg_walled_garden_index_clipit($hook, $type, $value, $params) {

    echo $hook;
    die();

    // return true to prevent other plugins from adding a front page
    return true;
}
*/

function home_user_account_page_handler($page_elements, $handler) {

    $base_dir = elgg_get_plugins_path() . 'clipit_theme/pages/account';
    switch ($handler) {

        case 'forgotpassword':
            require_once("$base_dir/forgotten_password.php");
        break;
        case 'resetpassword':
            require_once("$base_dir/reset_password.php");
            break;
        case 'register':
            require_once("$base_dir/register.php");
        break;
        case 'login':
            require_once("$base_dir/login.php");
        break;
        default:
            return false;
    }
    return true;
}

