<?php
require_once("../../kernel/config.php");

//Si le personnage est dans une ville
if ($characterTownId >= 1)
{
    //On fait une recherche dans la base de donnée pour récupérer la ville du personnage
    $townQuery = $bdd->prepare("SELECT * FROM car_towns 
    WHERE townId = ?");
    $townQuery->execute([$characterTownId]);

    //On fait une boucle sur les résultats
    while ($town = $townQuery->fetch())
    {
        //On récupère les informations de la ville
        $townId = stripslashes($town['townId']);
        $townPicture = stripslashes($town['townPicture']);
        $townName = stripslashes($town['townName']);
        $townDescription = stripslashes(nl2br($town['townDescription']));
        $townPriceInn = stripslashes($town['townPriceInn']);
    }
    $townQuery->closeCursor();
}
?>