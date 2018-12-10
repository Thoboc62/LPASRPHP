<?php
    require_once "config/bdd.conf.php";
    require_once "config/init.conf.php";
    require_once "includes/connexion.inc.php";

    include_once "includes/fonctions.inc.php";

    require_once "libs/Smarty.class.php";

    $name = "Thomas";
    $nom_page = "";

    $smarty = new Smarty();

    $smarty->setTemplateDir("templates/");
    $smarty->setCompileDir("templates_c/");

    $smarty->assign('name', $name);
    $smarty->assign('nom_page', $nom_page);
    $smarty->debugging = true;

    include_once "includes/head.inc.php";
    include_once "includes/menu.inc.php";

    $smarty->display("smarty_test.tpl");

    include_once "includes/footer.inc.php";
 ?>
