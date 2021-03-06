<?php

if (!function_exists('str_get_html')) {
    include dirname(__FILE__) . "/lib/simple_html_dom.php";
}

function elggx_fivestar_init() {

    elggx_fivestar_settings();

    $css_url = elgg_get_simplecache_url('css', 'basic');
    elgg_register_css('fivestar_css', $css_url);

    $js_rating = elgg_get_config('url') . "mod/elggx_fivestar/views/default/js/ui.stars.min.js";
    elgg_register_js('fivestar', $js_rating);

    elgg_register_plugin_hook_handler('view', 'all', 'elggx_fivestar_view');

    elgg_register_admin_menu_item('administer', 'elggx_fivestar', 'administer_utilities');

    // Upgrade settings
    $oldversion = elgg_get_plugin_setting('version', 'elggx_fivestar');
    // Check if we need to run an upgrade
    if (!$oldversion) {
        $plugin = elgg_get_plugin_from_id('elggx_fivestar');
        // Old versions of Elggx Fivestar used an array named view to save the Fivestar views which
        // results in an issue with the plugin to be shown in the plugin list in the admin section.
        // New name is elggx_fivestar_view and the old array needs to get deleted from the database.
        $view = $plugin->view;
        if (is_string($view)) {
            $plugin->__unset('view');
            $plugin->save();
        }
        // Set new version
        elgg_set_plugin_setting('version', '1.8.1', 'elggx_userpoints');
    } else if ($oldversion != '1.8.1') {
        // Set new version
        elgg_set_plugin_setting('version', '1.8.1', 'elggx_userpoints');
    }
}

/**
 * This method is called when the view plugin hook is triggered.
 * If a matching view config is found then the fivestar widget is
 * called.
 *
 * @param  integer  $hook The hook being called.
 * @param  integer  $type The type of entity you're being called on.
 * @param  string   $return The return value.
 * @param  array    $params An array of parameters for the current view
 * @return string   The html
 */
function elggx_fivestar_view($hook, $entity_type, $returnvalue, $params) {

    global $is_admin;

    $lines = explode("\n", elgg_get_plugin_setting('elggx_fivestar_view'));
    foreach ($lines as $line) {
        $options = array();
        $parms = explode(",", $line);
        foreach ($parms as $parameter) {
            preg_match("/^(\S+)=(.*)$/", trim($parameter), $match);
            $options[$match[1]] = $match[2];
        }

        if ($options['elggx_fivestar_view'] == $params['view']) {
            list($status, $html) = elggx_fivestar_widget($returnvalue, $params, $options);
            if (!$status) {
                continue;
            } else {
                return($html);
            }
        }
    }
}

/**
 * Handles voting on an entity
 *
 * @param  integer  $guid  The entity guid being voted on
 * @param  integer  $vote The vote
 * @return string   A status message to be returned to the client
 */
function elggx_fivestar_vote($guid, $vote) {

    $entity = get_entity($guid);
    $annotation_owner = elgg_get_logged_in_user_guid();

    $msg = null;

    if ($annotation = elgg_get_annotations(array('guid' => $entity->guid,
                                                 'type' => $entity->type,
                                                 'annotation_name' => 'fivestar',
                                                 'annotation_owner_guid' => $annotation_owner,
                                                 'limit' => 1))) {
        if ($vote == 0 && (int)elgg_get_plugin_setting('change_cancel')) {
            if (!elgg_trigger_plugin_hook('elggx_fivestar:cancel', 'all', array('entity' => $entity), false)) {
                elgg_delete_annotations(array('annotation_id' => $annotation[0]->id));
                $msg = elgg_echo('elggx_fivestar:deleted');
            }
        } else if (elgg_get_plugin_setting('change_cancel')) {
            update_annotation($annotation[0]->id, 'fivestar', $vote, 'integer', $annotation_owner, 2);
            $msg = elgg_echo('elggx_fivestar:updated');
        } else {
            $msg = elgg_echo('elggx_fivestar:nodups');
        }
    } else if ($vote > 0) {
        if (!elgg_trigger_plugin_hook('elggx_fivestar:vote', 'all', array('entity' => $entity), false)) {
            $entity->annotate('fivestar', $vote, 2, $annotation_owner);
        }
    } else {
        $msg = elgg_echo('elggx_fivestar:novote');
    }

    elggx_fivestar_setRating($entity);

    return($msg);
}

/**
 * Set the current rating for an entity
 *
 * @param  object   $entity  The entity to set the rating on
 * @return array    Includes the current rating and number of votes
 */
function elggx_fivestar_setRating($entity) {

    elggx_fivestar_su();

    $rating = elggx_fivestar_getRating($entity->guid);

    $entity->elggx_fivestar_rating = $rating['rating'];

    elggx_fivestar_su(true);

    return;
}

/**
 * Get an the current rating for an entity
 *
 * @param  integer  $guid  The entity guid being voted on
 * @return array    Includes the current rating and number of votes
 */
function elggx_fivestar_getRating($guid) {

    $rating = array('rating' => 0, 'votes' => 0);
    $entity = get_entity($guid);

    if (count($entity->getAnnotations('fivestar', 9999))) {
        $rating['rating'] = $entity->getAnnotationsAvg('fivestar');
        $rating['votes'] = count($entity->getAnnotations('fivestar', 9999));

        $modifier = 100 / (int)elgg_get_plugin_setting('stars');
        $rating['rating'] = round($rating['rating'] / $modifier, 1);
    }

    return($rating);
}

/**
 * Inserts the fivestar widget into the current view
 *
 * @param  string   $returnvalue  The original html
 * @param  array    $params  An array of parameters for the current view
 * @param  array    $guid  The fivestar view configuration
 * @return string   The original view or the view with the fivestar widget inserted
 */
function elggx_fivestar_widget($returnvalue, $params, $options) {

    $guid = $params['vars']['entity']->guid;

    if (!$guid) {
        return;
    }

    $widget = elgg_view("elggx_fivestar/voting", array('fivestar_guid' => $guid));

    // get the DOM
    $html = str_get_html($returnvalue);

    $match = 0;
    foreach ($html->find($options['tag']) as $element) {
        if ($element->$options['attribute'] == $options['attribute_value']) {
            $element->innertext .= $options['before_html'] . $widget . $options['after_html'];
            $match = 1;
            break;
        }
    }

    $returnvalue = $html;
    return(array($match, $returnvalue));
}

/**
 * Checks to see if the current user has already voted on the entity
 *
 * @param  guid   The entity guid
 * @return bool   Returns true/false
 */
function elggx_fivestar_hasVoted($guid) {

    $entity = get_entity($guid);
    $annotation_owner = elgg_get_logged_in_user_guid();

    $annotation = elgg_get_annotations(array('guids' => $entity->guid,
                                             'types' => $entity->type,
                                             'annotation_name' => 'fivestar',
                                             'annotation_owner_guid' => $annotation_owner,
                                             'limit' => 1));

    if (is_object($annotation[0])) {
        return(true);
    }

    return(false);
}

/**
 * Elevate user to admin.
 *
 * @param  bool $unsu  Return to original permissions
 * @return bool  is_admin true/false
 */
function elggx_fivestar_su($unsu=false) {
    global $is_admin;
    static $is_admin_orig = null;

    if (is_null($is_admin_orig)) {
        $is_admin_orig = $is_admin;
    }

    if ($unsu) {
        return $is_admin = $is_admin_orig;
    } else {
        return $is_admin = true;
    }
}

/**
 * Set default settings
 *
 */
function elggx_fivestar_settings() {

    // Set plugin defaults

    if (!(int)elgg_get_plugin_setting('stars')) {
        elgg_set_plugin_setting('stars', '5');
    }

    $change_vote = (int)elgg_get_plugin_setting('change_vote');
    if ($change_vote == 0) {
        elgg_set_plugin_setting('change_cancel', 0);
    } else {
        elgg_set_plugin_setting('change_cancel', 1);
    }
}

function elggx_fivestar_defaults() {

$elggx_fivestar_view = 'elggx_fivestar_view=object/blog, tag=div, attribute=class, attribute_value=elgg-content, before_html=<br />
elggx_fivestar_view=object/file, tag=div, attribute=class, attribute_value=elgg-content, before_html=<br />
elggx_fivestar_view=object/page_top, tag=div, attribute=class, attribute_value=elgg-content, before_html=<br />
elggx_fivestar_view=object/groupforumtopic, tag=div, attribute=class, attribute_value=elgg-content, before_html=<br />
elggx_fivestar_view=object/groupforumtopic, tag=div, attribute=class, attribute_value=elgg-output, before_html=<br />
elggx_fivestar_view=object/image, tag=div, attribute=class, attribute_value=elgg-output mbl, before_html=<br />
elggx_fivestar_view=object/bookmarks, tag=div, attribute=class, attribute_value=elgg-content, before_html=<br />';

elgg_set_plugin_setting('elggx_fivestar_view', $elggx_fivestar_view);
}

elgg_register_event_handler('init','system','elggx_fivestar_init');
elgg_register_action("elggx_fivestar/rate", $CONFIG->pluginspath . "elggx_fivestar/actions/rate.php", 'logged_in');
elgg_register_action("elggx_fivestar/settings", $CONFIG->pluginspath . "elggx_fivestar/actions/settings.php", 'admin');
elgg_register_action("elggx_fivestar/reset", $CONFIG->pluginspath . "elggx_fivestar/actions/reset.php", 'admin');
