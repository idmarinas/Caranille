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
    
    <p>Informations de la classe</p>
    
    <form method="POST" action="addRaceEnd.php">
        Image : <input type="text" name="adminRacePicture" class="form-control" placeholder="Nom" value="../../img/empty.png" required autofocus>
        Nom : <input type="text" name="adminRaceName" class="form-control" placeholder="Nom" required>
        Description : <br> <textarea class="form-control" name="adminRaceDescription" id="adminRaceDescription" rows="3" required></textarea>
        HP par niveau : <input type="number" name="adminRaceHpBonus" class="form-control" placeholder="HP par niveau" required>
        MP par niveau : <input type="number" name="adminRaceMpBonus" class="form-control" placeholder="MP par niveau" required>
        Force par niveau : <input type="number" name="adminRaceStrengthBonus" class="form-control" placeholder="Force par niveau" required>
        Magie par niveau : <input type="number" name="adminRaceMagicBonus" class="form-control" placeholder="Magie par niveau" required>
        Agilité par niveau : <input type="number" name="adminRaceAgilityBonus" class="form-control" placeholder="Agilité par niveau" required>
        Défense par niveau : <input type="number" name="adminRaceDefenseBonus" class="form-control" placeholder="Défense par niveau" required>
        Défense Magique par niveau : <input type="number" name="adminRaceDefenseMagicBonus" class="form-control" placeholder="Défense Magique par niveau" required>
        Sagesse par niveau : <input type="number" name="adminRaceWisdomBonus" class="form-control" placeholder="Sagesse par niveau" required>
        Prospection par niveau : <input type="number" name="adminRaceProspectingBonus" class="form-control" placeholder="Prospection par niveau" required>
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