<?php
    if(isset($_SESSION['notifications'])) {
        if($_SESSION['notifications']['result']==true) {
            $color_notification = "success";
        }
        else{
            $color_notification = "danger";
        }
        $smarty->assign('color_notification', $color_notification);
        $smarty->display("notifications.tpl");
        unset($_SESSION['notifications']);
}

?>