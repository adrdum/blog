{include file="haut.tpl"}

<h2>{$titre}</h2>

<form id="form" action="{$form_action}" method="post" enctype="multipart/form-data">
    <div class="clearfix">
        <label for="titre">Titre</label>
        <div class="input">
            <input type="text" name="titre" id="titre" value="{$article_titre|escape:'html'}">
        </div>
    </div>

    <div class="clearfix">
        <label for="texte">Texte</label>
        <div class="input">
            <textarea name="texte" id="texte">{$article_texte|escape:'html'}</textarea>
        </div>
    </div>

    <div class="clearfix">
        <label for="tag">Tag(s)</label>
        <div class="input">
            <input type="text" name="tag" id="tag" value="{$article_tag|escape:'html'}">
        </div>
    </div>

    {if $article_image_actuelle}
        <div class="clearfix checkbox_inline">
            <label for="suppr">Supprimer image</label>
            <div class="input">
                <input {if $article_image_suppr} checked="checked" {/if} type="checkbox" name="suppr" id="suppr">
            </div>
        </div>
        <div class="cadre_petite_vignette">
            <img class="petite_vignette" src="{$article_image_actuelle}">
        </div>
    {/if}

    <div class="clearfix">
        <label for="image">{if $article_image_actuelle}Remplacer image{else}Image{/if}</label>
        <div class="input">
            <input type="file" id="image" name="image">
        </div>
    </div>

    <div class="clearfix checkbox_inline">
        <label for="publie">Publié</label>
        <div class="input">
            <input {if $article_publie} checked="checked" {/if} type="checkbox" name="publie" id="publie">
        </div>
    </div>

    <input type="hidden" name="submit" value="1">

    <div class="form-actions">
        <input id="form_submit" type="submit" value="{$form_bouton_value}" class="btn btn-large btn-primary">
    </div>

</form>
{literal}
<script type="text/javascript">
    $(function(){
        $("#form_submit").click(function(){
            cacherNotifTimeout();
        });
        $("#form").submit(function(){
            var titre = $("#titre").val();
            var texte = $("#texte").val();
            if (!titre || !texte){
                $(".alert>span").html("Veuillez renseigner les champs « Titre » et « Texte ».");
                $(".alert").removeClass()
                           .addClass("alert")
                           .addClass("alert-error")
                           .slideDown();
                $('html, body').animate({
                    scrollTop:$("html").offset().top
                }, 'slow');
                return false;
            }
            return true;
        });
    });
</script>
{/literal}

{include file="bas.tpl"}
