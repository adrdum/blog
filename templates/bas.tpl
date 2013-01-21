          </div>
          <nav class="span4">
            <h2>Menu</h2>
            <form action="." method="get">
                <div class="checkbox_inline">
                    <label for="recherche">Recherche : </label>
                    <input type="text" name="recherche" placeholder="{$recherche_placeholder}"
                    {if $recherche}
                        value="{$recherche|escape:'html'}"
                    {/if}
                    class="span3">&nbsp;
                    {if $nonpublie}
                        <input type="hidden" name="nonpublie" value="1">
                    {/if}
                    <input type="submit" value="OK" class="btn btn-primary">
                </div>
            </form>
            <ul>
                <li><a href=".">Accueil</a></li>
                {if $utilisateur.type eq 'visiteur'}
                    <li><a href="connexion.php">Connexion</a></li>
                {else}
                    <li><a href="article.php">Rédiger un article</a></li>
                    <li><a href=".?nonpublie=1">Articles non publiés</a></li>
                    <li><a href="connexion.php?logout=1">Déconnexion</a></li>
                {/if}
            </ul>
          </nav>
        </div>
      </div>
      <footer>
        <p>&copy; Nilsine &amp; ULCO 2012</p>
      </footer>
    </div>
    <script type="text/javascript" src="assets/js/notification.js.php"></script>
  </body>
</html>
