{include file="haut.tpl"}

<h2>{$titre}</h2>

{$pagination_html}

{if $articles}
    {section name=i loop=$articles}
    <article>
        <h3>{$articles[i].titre|escape:'html'}</h3>
          {if $articles[i].image}
            <div class="image">
                <a href="./data/article_image/{$articles[i].image}.jpg">
                    <img alt="{$articles[i].titre|escape:'html'}" src="./vignette.jpg.php?id={$articles[i].image}">
                </a>
            </div>
          {/if}
        <div class="ligne ligne_date">
            <span class="date">
                Publié le {$articles[i].date|date_format:"%e/%m/%Y à %k:%M"}{if $articles[i].date_modif ne ''}, modifié le {$articles[i].date_modif|date_format:"%e/%m/%Y à %k:%M"}{/if}
            </span>
          {if $utilisateur.type eq 'admin'}
            <span class="bouton">
                <a href="article.php?id={$articles[i].id}" class="btn btn-primary btn-mini">Modifier</a>
                <a href="supprimer-article.php?id={$articles[i].id}" class="btn btn-danger btn-mini">Supprimer</a>
            </span>
          {/if}
        </div>
        <div class="ligne">
            {$articles[i].texte|escape:'html'|nl2br}
        </div>
          {if $articles[i].tag}
            <div class="ligne">
              {foreach from=$articles[i].tag item=tag}
                <a href=".?tag={$tag|escape:'url'}"><span class="tag"><img src="./assets/images/tag.png"> {$tag}</span></a>
              {/foreach}
            </div>
          {/if}
    </article>
    {/section}
{else}
    <p>{$texte_pas_articles}</p>
{/if}

{$pagination_html}

{include file="bas.tpl"}
