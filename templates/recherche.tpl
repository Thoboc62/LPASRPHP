<!-- Page Content -->
<div class="container">
    <div class="row">
        <div class="col-lg-12 text-center">
            <h1 class="mt-5">Recherche sur le site</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12 text-center">
            <form action="recherche.php" method="get" enctype="multipart/form-data" id="form_article">
                <input type="hidden" value="1" name="afficher"/>
                <div class="form-group">
                    <label for"titre" class="col-from-label">Votre recherche:</label>
                    <input type="text" class="form-control" id="recherche" name="recherche" placeholder="Votre recherche..." value="" required/>
                </div>
                <button type="submit" class="btn btn-primary" name="" value="">Rechercher</button>
            </form>
        </div>
    </div>

    {if $afficher_resultats eq 1}
        <div class="row">
            {foreach from=$tab_result item=i}
                <div class="col-md-6">
                    <div class="card mt-4">
                        <img class="card-img-top" src="img/{$i.id_articles}.jpg" alt="{$i.id_articles}"/>
                        <div class="card-body">
                            <h4 class="card-title">{$i.titre}</h4>
                            <p class="card-text">{$i.texte}</p>
                            <a href="#" class="btn btn-primary">Créé le: {$i.date}</a>
                            <a href="article.php?action=modifier&id={$i.id_articles}" class="btn btn-warning">Modifier</a>
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>

        <div class="row">
            <nav aria-label="Page navigation" class="mx-auto mt-4">
                <ul class="pagination">
                    {for $i=1 to $nb_pages}
                        <li class="page-item">
                            <a class="page-link" href="?afficher=1&p={$i}&recherche={$recherche}">{$i}</a>
                        </li>
                    {/for}
                </ul>
            </nav>
        </div>
    {/if}
</div>