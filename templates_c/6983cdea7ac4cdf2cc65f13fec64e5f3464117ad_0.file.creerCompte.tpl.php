<?php
/* Smarty version 3.1.33, created on 2018-12-10 12:41:38
  from 'D:\wamp64\www\MonBlog\templates\creerCompte.tpl' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '3.1.33',
  'unifunc' => 'content_5c0e5f02c80f67_25587387',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '6983cdea7ac4cdf2cc65f13fec64e5f3464117ad' => 
    array (
      0 => 'D:\\wamp64\\www\\MonBlog\\templates\\creerCompte.tpl',
      1 => 1544445683,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5c0e5f02c80f67_25587387 (Smarty_Internal_Template $_smarty_tpl) {
?><!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Créer un compte</h1>
            <!--<?php echo '<?php ';?>include "includes/notification.inc.php"; <?php echo '?>';?>-->
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
</div><?php }
}
