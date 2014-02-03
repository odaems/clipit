<?php
$title = elgg_extract('title', $vars, '');
$body = elgg_extract('body', $vars, '');
$footer = elgg_extract('footer', $vars, '');

// modal header
echo '<div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>';
if($title)
    echo '<h2 class="modal-title">'.$title.'</h2>';
echo '</div>';

// modal body
echo '<div class="modal-body">'.$body.'</div>';

// modal footer
if($footer)
    echo '<div class="modal-footer">'.$footer.'</div>';
