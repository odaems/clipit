<?php
/**
 * Elgg login action
 *
 * @package Elgg.Core
 * @subpackage User.Authentication
 */

// set forward url
if (!empty($_SESSION['last_forward_from'])) {
    $forward_url = $_SESSION['last_forward_from'];
    unset($_SESSION['last_forward_from']);
} elseif (get_input('returntoreferer')) {
    $forward_url = REFERER;
} else {
    // forward to main index page
    $forward_url = '';
}

$username = get_input('username');
$password = get_input('password', null, false);
$persistent = (bool) get_input("persistent");
$result = false;

try {
    \clipit\ClipitUser::login($username, $password, $persistent);
} catch (LoginException $e) {
    register_error($e->getMessage());
    forward(REFERER);
}

// elgg_echo() caches the language and does not provide a way to change the language.
// @todo we need to use the config object to store this so that the current language
// can be changed. Refs #4171
if ($user->language) {
    $message = elgg_echo('loginok', array(), $user->language);
} else {
    $message = elgg_echo('loginok');
}

system_message($message);
forward($forward_url);
