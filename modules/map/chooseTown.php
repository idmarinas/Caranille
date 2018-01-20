<?php require_once("../../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur est déjà dans un lieu on le redirige vers le lieu
if ($characterplaceId >= 1) { exit(header("Location: ../../modules/town/index.php")); }
//S'il y a actuellement un combat on redirige le joueur vers le module battle
if ($battleRow > 0) { exit(header("Location: ../../modules/battle/index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['placeId'])
&& isset($_POST['token'])
&& isset($_POST['enter']))
{
    //Si le token de sécurité est correct
    if ($_POST['token'] == $_SESSION['token'])
    {
        //On supprime le token de l'ancien formulaire
        $_SESSION['token'] = NULL;

        //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
        if (ctype_digit($_POST['placeId'])
        && $_POST['placeId'] >= 1)
        {
            //On récupère l'id du formulaire précédent
            $placeId = htmlspecialchars(addslashes($_POST['placeId']));

            //On fait une requête pour vérifier si le joueur peut accèder à le lieu choisie
            $townQuery = $bdd->prepare('SELECT * FROM car_places
            WHERE placeChapter <= ?
            AND placeId = ?');
            $townQuery->execute([$characterChapter, $placeId]);
            $townRow = $townQuery->rowCount();

            //Si le lieu existe pour le joueur il y entre
            if ($townRow >= 1) 
            {
                //On met le personnage à jour
                $updatecharacter = $bdd->prepare("UPDATE car_characters SET
                characterplaceId = :characterplaceId
                WHERE characterId = :characterId");
                $updatecharacter->execute(array(
                'characterplaceId' => $placeId, 
                'characterId' => $characterId));
                $updatecharacter->closeCursor();

                header("Location: ../../modules/town/index.php");
            }
            //Si le lieu n'exite pas pour le joueur on le prévient
            else
            {
                echo "le lieu choisie est invalide";
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