<?php

    //fonction permettant de hacher la chaine de caractères en paramètre
    function cryptPassword($mdp) {
        $mdp_crypt = sha1($mdp);
        return $mdp_crypt;
    }

    //fonction permettant la génération d'un SID
    function sid($email) {
        $sid = md5($email.time());
        return $sid;
    }

    //Fonction de retour d'index pour la pagination (article de départ de la sélection)
    function getIndex($page_courante, $nb_articles_par_page) {
        $index = ($page_courante-1)*$nb_articles_par_page;
        return $index;
    }

    //fonction retournant le nombre d'articles publiés
    function nbTotalArticlesPublie($bdd) {
        /* @var $bdd PDO */
        $sql = "SELECT COUNT(*) AS nb_total_article_publie FROM articles WHERE publie = 1";
        $sth = $bdd->prepare($sql);
        $sth->execute();
        $tab_result=$sth->fetch(PDO::FETCH_ASSOC);
        return $tab_result['nb_total_article_publie'];
    }

    //fonction retournant le nombre d'articles correspondant au motif de recherche
    function nbResultatsRecherche($bdd, $recherche) {
        /* @var $bdd PDO */
        $sql = "SELECT count(*) as nb_resultats FROM articles WHERE titre LIKE :titre OR texte LIKE :texte AND publie=1";
        $sth = $bdd->prepare($sql);
        $sth->bindValue(":titre", "%".$recherche."%", PDO::PARAM_STR);
        $sth->bindValue(":texte", "%".$recherche."%", PDO::PARAM_STR);
        $sth->execute();
        $tab_result=$sth->fetch(PDO::FETCH_ASSOC);
        return $tab_result['nb_resultats'];
    }

 ?>
