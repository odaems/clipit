<?php
/**
 * Footer clipit navigation menu
 *
 */

$items = elgg_extract('menu', $vars);
$class = elgg_extract('class', $vars, false);

/*foreach ($vars['menu'] as $section => $menu_items) {

    echo elgg_view('navigation/menu/elements/section', array(
        'items' => $menu_items,
        'class' => "$class",
        'section' => $section,
        'name' => $vars['name']
    ));
}
*/
$viewer = elgg_get_logged_in_user_entity();
?>
<ul class="<?php echo $class; ?>">
    <li>
        <a class="avatar-user" href="<?php echo $CONFIG->wwwroot; ?>profile/<?php echo $viewer->username; ?>">
        <?php echo elgg_view('output/img', array(
            'src' => $viewer->getIconURL('small'),
            'alt' => $viewer->name,
            'title' => elgg_echo('profile'),
            'class' => 'elgg-border-plain elgg-transition',
        )); ?>
            User
        </a>
    </li>
    <li class="separator">|</li>
    <li><a href="<?php echo $CONFIG->wwwroot; ?>profile/<?php echo $viewer->username; ?>">My groups</a></li>
</ul>