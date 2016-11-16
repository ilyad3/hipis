<?php
function src_url($src) {
    $src = preg_replace('/((?:\w+:\/\/|www\.)[\w.\/%\d&?#+=-]+)/i', '<a target="_blank" href="\1">\1</a>', $src);
    return $src;
}
?>