
<?php

    session_start();

    require_once "config/init.conf.php";
    require_once "config/bdd.conf.php";
    include_once "includes/connexion.inc.php";
    include_once "includes/head.inc.php";

    $nom_page = "gestionarticles";

    include "includes/menu.inc.php";

    if(!$is_connect) {
        $_SESSION['notifications']['message'] = "Vous devez vous connecter pour ajouter des articles.";
        $_SESSION['notifications']['result'] = false;
        header("Location: connexion.php");
        exit();
    }

    if(isset($_POST['submit'])) {
        print_r2($_POST);
        print_r2($_FILES);

        //Vérifier si la case a été cochée ou non
        $publie = isset($_POST['publie']) ? 1 : 0;

        //Requête d'insertion des articles
        $cur_date = date("Y-m-d");
        $inserer_article = "INSERT INTO articles(titre, texte, date, publie) VALUES (:titre, :texte, :date, :publie)";
        /* @var $bdd PDO */

        //Préparation de la requête
        $sth = $bdd->prepare($inserer_article);

        //Sécuriser les paramètres
        $sth->bindValue(":titre", $_POST['titre'], PDO::PARAM_STR);
        $sth->bindValue(":texte", $_POST['texte'], PDO::PARAM_STR);
        $sth->bindValue(":date", $cur_date, PDO::PARAM_STR);
        $sth->bindValue(":publie", $publie, PDO::PARAM_INT);

        //Exécution de la requête
        $result = $sth->execute(); //on stocke dans $result le resultat de la requete, pour savoir si elle a fonctionné
        if($result == TRUE) {
            $notification = "Félicitations votre article est publié !";
            $succes_notification = true;
        }
        else {
            $notification = "Erreur d'insertion dans la base de données.";
            $succes_notification = false;
        }

        //Récupération du dernier id pour trouver l'id de l'article à insérer dans la BDD
        $id_article = $bdd->lastInsertId();

        //Vérification de l'image
        if($_FILES['image']['error']==0) {
            $extension = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
            $result_img = move_uploaded_file($_FILES['image']['tmp_name'], "img/" . $id_article . "." . $extension); //on stocke dans $result_img le resultat de la commande

            $notification .= $result_img == TRUE ? "" : "Erreur lors du déplacement de l'image."; //on ajoute à la notification précédente la nouvelle (concaténation)
        }

        //Variables de session
        $_SESSION['notifications']['message'] = $notification;
        $_SESSION['notifications']['result'] = $succes_notification;

        //Redirection vers la page d'Accueil
        header('Location: index.php');
        //exit(); //arrêter l'exécution à cet endroit, le reste de la page ne sera pas traité

    }

?>

<?php
    $action = $_GET['action'];
 ?>

<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5"><?php echo ucfirst($action) ; ?> un article</h1>
            <?php include "includes/notification.inc.php"; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 text-center">
            <form action="article.php" method="post" enctype="multipart/form-data" id="form_article">
                <div class="form-group">
                    <label for"titre" class="col-from-label">Titre</label>
                    <input type="text" class="form-control" id="titre" name="titre" placeholder="Titre de votre article" value="" required/>
                </div>
                <div class="form-group">
                    <label for"texte">Texte</label>
                    <textarea class="form-control" id="texte" name="texte" rows="3" required></textarea>
                </div>
                <div class="form-group">
                    <div class="custom-file">
                        <input type="file" id="image" name="image" class="custom-file-input" required>
                        <label class="custom-file-label" for="image">Choisir un fichier</label>
                    </div>
                </div>
                <div class="form-group">
                    <div class="form-check">
                        <label for="publie" class="form-check-label">
                            <input class="form-check-input" name="publie" id="publie" type="checkbox" value="1">Publier ?
                        </label>
                </div>
                <button type="submit" class="btn btn-primary" name="submit" value="<?php echo $action ; ?>"><?php echo ucfirst($action) ; ?> l'article</button>
            </form>
        </div>
    </div>
</div>

<?php include 'includes/footer.inc.php' ?>
