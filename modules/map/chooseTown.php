<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur est déjà dans une ville on le redirige vers la ville
if ($characterTownId >= 1) { exit(header("Location: ../../modules/town/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['townId'])
&& isset($_POST['token'])
&& isset($_POST['enter']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['townId'])
        && $_POST['townId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $townId = htmlspecialchars(addslashes($_POST['townId']));

            //On fait une requête pour vérifier si le joueur peut accèder à la ville choisie
            $townQuery = $bdd->prepare('SELECT * FROM car_towns
            WHERE townChapter <= ?
            AND townId = ?');
            $townQuery->execute([$characterChapter, $townId]);
            $townRow = $townQuery->rowCount();

            //Si la ville existe pour le joueur il y entre
            if ($townRow >= 1) 
            {
                //On met le personnage à jour
                $updatecharacter = $bdd->prepare("UPDATE car_characters SET
                characterTownId = :characterTownId
                WHERE characterId = :characterId");
                $updatecharacter->execute(array(
                'characterTownId' => $townId, 
                'characterId' => $characterId));
                $updatecharacter->closeCursor();

                header("Location: ../../modules/town/index.php");
            }
            //Si la ville n'exite pas pour le joueur on le prévient
            else
            {
                echo "La ville choisie est invalide";
            }
            $townQuery->closeCursor();
        }
        //Si tous les champs numérique ne contiennent pas un nombre
        else
        {
            echo "Erreur : Les champs de type numérique ne peuvent contenir qu'un nombre entier";
        }
    }
    //Si le token de sécurité n'est pas correct
    else
    {
        echo "Erreur : Impossible de valider le formulaire, veuillez réessayer";
    }
}
//Si toutes les variables $_POST n'existent pas
else 
{
    echo "Tous les champs n'ont pas été rempli";
}

require_once("../../html/footer.php"); ?>