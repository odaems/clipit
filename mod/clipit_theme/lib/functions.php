<?php
/**
 * Created by JetBrains PhpStorm.
 * User: equipo
 * Date: 4/02/14
 * Time: 10:44
 * To change this template use File | Settings | File Templates.
 */

function activity_color(){

}

// Inutilizado
function OLD_add_landing_tool_option($name, $label, $view, $default_on = true) {
    global $CONFIG;

    if (!isset($CONFIG->landing_tool_options)) {
        $CONFIG->landing_tool_options = array();
    }

    $landing_tool_option = new stdClass;

    $landing_tool_option->name = $name;
    $landing_tool_option->label = $label;
    $landing_tool_option->view = $view;
    $landing_tool_option->default_on = $default_on;

    $CONFIG->landing_tool_options[] = $landing_tool_option;
}


function add_landing_tool_option($context, array $options) {
    global $CONFIG;

    if (!isset($CONFIG->landing_tool_options)) {
        $CONFIG->landing_tool_options = array();
    }

    $CONFIG->landing_tool_options[$context][] = $options;
}
