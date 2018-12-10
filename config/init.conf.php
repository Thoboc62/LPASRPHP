 <?php

     //affichage des erreur et avertissements PHP
     error_reporting(E_ALL);
     ini_set("display_errors" , 1);

     //
     function print_r2($param) {
         echo '<pre>';
         print_r($param);
         echo '</pre>';
     }

     define('_NB_ART_PAR_PAGE', 2);

 ?>
