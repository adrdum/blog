<?php
// script permettant de cacher les notifications si délai écoulé ou clique

define('BLOG_EXEC', true);

require('../../include/configuration.inc.php');

header('Content-type: text/javascript');
?>

function cacherNotif(){
    $(".alert").slideUp();
}

function cacherNotifTimeout(){
    if (typeof(notif_timeout) != "undefined")
        window.clearTimeout(notif_timeout);
    notif_timeout = setTimeout(cacherNotif, <?php echo $config['notif_timeout'] ?>);
}

$(function(){
    cacherNotifTimeout();
    $(".cacher_notif").live("click", function(){cacherNotif();});
});
