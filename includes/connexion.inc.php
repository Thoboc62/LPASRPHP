<?php

    $is_connect = FALSE;

    if(isset($_COOKIE['sid']) AND !empty($_COOKIE['sid'])) {

        $select = "SELECT count(*) as nb_sid, nom, prenom FROM users WHERE sid=:sid";

        /* @var $bdd PDO */
        $sth = $bdd->prepare($select);
        $sth->bindValue(":sid", $_COOKIE['sid'], PDO::PARAM_STR);
        $sth->execute();

        $tab_result = $sth->fetch(PDO::FETCH_ASSOC);

        if($tab_result['nb_sid'] > 0) {
            $is_connect = TRUE;
            $nom_connect = $tab_result['nom'];
            $prenom_connect = $tab_result['prenom'];
            //print_r2($tab_result);
        }
    }

?>
