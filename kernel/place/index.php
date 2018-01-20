<?php
require_once("../../kernel/config.php");

//Si le personnage est dans un lieu
if ($characterplaceId >= 1)
{
    //On fait une recherche dans la base de donnée pour récupérer le lieu du personnage
    $townQuery = $bdd->prepare("SELECT * FROM car_places 
    WHERE placeId = ?");
    $townQuery->execute([$characterplaceId]);

    //On fait une boucle sur les résultats
    while ($town = $townQuery->fetch())
    {
        //On récupère les informations du lieu
        $placeId = stripslashes($town['placeId']);
        $placePicture = stripslashes($town['placePicture']);
        $placeName = stripslashes($town['placeName']);
        $placeDescription = stripslashes(nl2br($town['placeDescription']));
        $placePriceInn = stripslashes($town['placePriceInn']);
    }
    $townQuery->closeCursor();
}
?>