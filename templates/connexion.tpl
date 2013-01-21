{include file="haut.tpl"}

<h2>{$titre}</h2>

<form id="form_connexion" action="connexion.php" method="post">
    <p>Saisissez les identifiants choisis lors de votre inscription.</p>

    <div class="clearfix">
        <label for="email">E-mail</label>
        <div class="input">
            <input type="email" name="email" id="email" value="{$email}">
        </div>
    </div>

    <div class="clearfix">
        <label for="mdp">Mot de passe</label>
        <div class="input">
            <input type="password" name="mdp" id="mdp" value="">
        </div>
    </div>

    <div class="form-actions">
        <input type="submit" name="submit" value="Se connecter" class="btn btn-large btn-primary" id="form_submit">
    </div>
</form>
{literal}
<script type="text/javascript">
    $(function(){
        $("#form_submit").click(function(){
            cacherNotifTimeout();
        });
        $("#form_connexion").submit(function(){
            var email = $("#email").val();
            var mdp = $("#mdp").val();
            if (!email || !mdp){
                $(".alert>span").html("Veuillez renseigner tous les champs.");
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
