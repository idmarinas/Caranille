<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['add']))
{
    ?>
    
    <p>Informations du lieu</p>

    <form method="POST" action="addPlaceEnd.php">
        Image : <input type="text" name="adminplacePicture" class="form-control" placeholder="Image" value="../../img/empty.png" required>
        Nom : <input type="text" name="adminplaceName" class="form-control" placeholder="Nom" required>
        Description : <br> <textarea class="form-control" name="adminplaceDescription" id="adminplaceDescription" rows="3"></textarea>
        Prix de l'auberge : <input type="number" name="adminplacePriceInn" class="form-control" placeholder="Prix de l'auberge" value="0" required>
        lieu disponible au chapitre : <input type="number" name="adminplaceChapter" class="form-control" placeholder="lieu disponible au chapitre" value="1" required>
        Lieu accessible aux autres joueurs : <select name="adminplaceAccess" class="form-control">
	        <option value="Yes">Oui</option>
	        <option value="No">Non</option>
	    </select>
        <input name="finalAdd" class="btn btn-default form-control" type="submit" value="Ajouter">
    </form>
    
    <hr>

    <form method="POST" action="index.php">
        <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
    </form>
    
    <?php
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");