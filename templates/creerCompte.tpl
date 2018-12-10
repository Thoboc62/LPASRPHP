<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Créer un compte</h1>
            <!--<?php include "includes/notification.inc.php"; ?>-->
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 text-center">
            <form action="creerCompte.php" method="post" enctype="multipart/form-data" id="form_article">
                <div class="form-group">
                    <label for"nom" class="col-from-label">Nom:</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="" value="" required/>
                </div>
                <div class="form-group">
                    <label for"prenom" class="col-from-label">Prénom:</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="" value="" required/>
                </div>
                <div class="form-group">
                    <label for"email" class="col-from-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="" value="" required/>
                </div>
                <div class="form-group">
                    <label for"mdp" class="col-from-label">Mot de passe:</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="" value="" required/>
                </div>
                <!--<div class="form-group">
                    <label for"sid" class="col-from-label">SID:</label>
                    <input type="text" class="form-control" id="sid" name="sid" placeholder="" value="" readonly/>
                </div>-->

                <button type="submit" class="btn btn-primary" name="submit" value="creerCompte">Valider l'inscription</button>
            </form>
        </div>
    </div>
</div>