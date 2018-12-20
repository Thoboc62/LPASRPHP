<!DOCTYPE html>
<html lang="en">
    <body>

        <!-- Page Content -->
        <div class="container">

            <div class="row">
                <div class="col-lg-12 text-center">
                    <h1 class="mt-5">Blizzard's fan page</h1>
                    {include file='notifications.tpl'}
                </div>
            </div>

            <div class="row">
                {foreach from=$tab_articles item=i}
                <div class="col-md-6">
                    <div class="card mt-4">
                        <img class="card-img-top" src="img/{$i.id_articles}.jpg" alt="{$i.id_articles}"/>
                        <div class="card-body">
                            <h4 class="card-title">{$i.titre}</h4>
                            <p class="card-text">{$i.texte}</p>
                            <a href="#" class="btn btn-primary">Créé le: {$i.date}</a>
                            <a href="article.php?action=modifier&id={$i.id_articles}" class="btn btn-warning">Modifier</a>
                            <a href="article.php?action=supprimer&id={$i.id_articles}" class="btn btn-danger">Supprimer</a>
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
                                <a class="page-link" href="?p={$i}">{$i}</a>
                            </li>
                        {/for}
                    </ul>
                </nav>
            </div>
            
        </div>

    </body>

</html>
