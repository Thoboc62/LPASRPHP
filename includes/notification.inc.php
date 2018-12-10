<!-- EN TETE -->

<?php

if(isset($_SESSION['notifications'])) {
    $color_notification = $_SESSION['notifications']['result'] == true ? "success" : "danger";
    ?>
    <div class="alert alert-<?php echo $color_notification; ?> alert-dismissible fade show" role="alert">
        <?php
                echo $_SESSION['notifications']['message'];
                unset($_SESSION['notifications']);
         ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php } ?>
