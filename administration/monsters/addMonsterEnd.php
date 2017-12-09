<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminMonsterPicture'])
&& isset($_POST['adminMonsterName'])
&& isset($_POST['adminMonsterLevel'])
&& isset($_POST['adminMonsterDescription'])
&& isset($_POST['adminMonsterHp'])
&& isset($_POST['adminMonsterMp'])
&& isset($_POST['adminMonsterStrength'])
&& isset($_POST['adminMonsterMagic'])
&& isset($_POST['adminMonsterAgility'])
&& isset($_POST['adminMonsterDefense'])
&& isset($_POST['adminMonsterDefenseMagic'])
&& isset($_POST['adminMonsterGold'])
&& isset($_POST['adminMonsterExperience'])
&& isset($_POST['finalAdd']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminMonsterLevel']) 
    && ctype_digit($_POST['adminMonsterHp'])
    && ctype_digit($_POST['adminMonsterMp'])
    && ctype_digit($_POST['adminMonsterStrength'])
    && ctype_digit($_POST['adminMonsterMagic'])
    && ctype_digit($_POST['adminMonsterAgility'])
    && ctype_digit($_POST['adminMonsterDefense'])
    && ctype_digit($_POST['adminMonsterDefenseMagic'])
    && ctype_digit($_POST['adminMonsterGold'])
    && ctype_digit($_POST['adminMonsterExperience'])
    && $_POST['adminMonsterLevel'] >= 0
    && $_POST['adminMonsterHp'] >= 0
    && $_POST['adminMonsterMp'] >= 0
    && $_POST['adminMonsterStrength'] >= 0
    && $_POST['adminMonsterMagic'] >= 0
    && $_POST['adminMonsterAgility'] >= 0
    && $_POST['adminMonsterDefense'] >= 0
    && $_POST['adminMonsterDefenseMagic'] >= 0
    && $_POST['adminMonsterGold'] >= 0
    && $_POST['adminMonsterExperience'] >= 0)
    {
        //On récupère les informations du formulaire
        $adminMonsterPicture = htmlspecialchars(addslashes($_POST['adminMonsterPicture']));
        $adminMonsterName = htmlspecialchars(addslashes($_POST['adminMonsterName']));
        $adminMonsterLevel = htmlspecialchars(addslashes($_POST['adminMonsterLevel']));
        $adminMonsterDescription = htmlspecialchars(addslashes($_POST['adminMonsterDescription']));
        $adminMonsterHp = htmlspecialchars(addslashes($_POST['adminMonsterHp']));
        $adminMonsterMp = htmlspecialchars(addslashes($_POST['adminMonsterMp']));
        $adminMonsterStrength = htmlspecialchars(addslashes($_POST['adminMonsterStrength']));
        $adminMonsterMagic = htmlspecialchars(addslashes($_POST['adminMonsterMagic']));
        $adminMonsterAgility = htmlspecialchars(addslashes($_POST['adminMonsterAgility']));
        $adminMonsterDefense = htmlspecialchars(addslashes($_POST['adminMonsterDefense']));
        $adminMonsterDefenseMagic = htmlspecialchars(addslashes($_POST['adminMonsterDefenseMagic']));             
        $adminMonsterGold = htmlspecialchars(addslashes($_POST['adminMonsterGold']));
        $adminMonsterExperience = htmlspecialchars(addslashes($_POST['adminMonsterExperience']));

        //On ajoute le monstre dans la base de donnée
        $addMonster = $bdd->prepare("INSERT INTO car_monsters VALUES(
        NULL,
        :adminMonsterPicture,
        :adminMonsterName,
        :adminMonsterDescription,
        :adminMonsterLevel,
        :adminMonsterHp,
        :adminMonsterMp,
        :adminMonsterStrength,
        :adminMonsterMagic,
        :adminMonsterAgility,
        :adminMonsterDefense,
        :adminMonsterDefenseMagic,
        :adminMonsterExperience,
        :adminMonsterGold)");
        $addMonster->execute([
        'adminMonsterPicture' => $adminMonsterPicture,
        'adminMonsterName' => $adminMonsterName,
        'adminMonsterDescription' => $adminMonsterDescription,
        'adminMonsterLevel' => $adminMonsterLevel,
        'adminMonsterHp' => $adminMonsterHp,
        'adminMonsterMp' => $adminMonsterMp,
        'adminMonsterStrength' => $adminMonsterStrength,
        'adminMonsterMagic' => $adminMonsterMagic,
        'adminMonsterAgility' => $adminMonsterAgility,
        'adminMonsterDefense' => $adminMonsterDefense,
        'adminMonsterDefenseMagic' => $adminMonsterDefenseMagic,
        'adminMonsterExperience' => $adminMonsterExperience,
        'adminMonsterGold' => $adminMonsterGold]);
        $addMonster->closeCursor();
        ?>

        Le monstre a bien été crée

        <hr>
            
        <form method="POST" action="index.php">
            <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
        </form>
        
        <?php
    }
    //Si tous les champs numérique ne contiennent pas un nombre
    else
    {
        echo "Erreur : Les champs de type numérique ne peuvent contenir qu'un nombre entier";
    }
}
//Si toutes les variables $_POST n'existent pas
else
{
    echo "Erreur : Tous les champs n'ont pas été rempli";
}

require_once("../html/footer.php");