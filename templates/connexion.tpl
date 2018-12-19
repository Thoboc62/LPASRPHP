<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Se connecter</h1>
            {include file='notifications.tpl'}
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 text-center">
            <form action="connexion.php" method="post" enctype="multipart/form-data" id="form_article">
                <div class="form-group">
                    <label for"login" class="col-from-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre adresse email" value="" required/>
                </div>
                <div class="form-group">
                    <label for"mdp" class="col-from-label">Mot de passe:</label>
                    <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Votre mot de passe" value="" required/>
                </div>
                <button type="submit" class="btn btn-primary" name="submit" value="connecter">Se connecter</button>
            </form>
        </div>
    </div>
</div>