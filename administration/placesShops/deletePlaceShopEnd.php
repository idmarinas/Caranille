<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPlaceShopPlaceId'])
&& isset($_POST['adminTownShopShopId'])
&& isset($_POST['finalDelete']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminPlaceShopPlaceId'])
    && ctype_digit($_POST['adminTownShopShopId'])
    && $_POST['adminPlaceShopPlaceId'] >= 1
    && $_POST['adminTownShopShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminPlaceShopPlaceId = htmlspecialchars(addslashes($_POST['adminPlaceShopPlaceId']));
        $adminTownShopShopId = htmlspecialchars(addslashes($_POST['adminTownShopShopId']));

        //On fait une requête pour vérifier si le lieu choisie existe
        $placeQuery = $bdd->prepare('SELECT * FROM car_places 
        WHERE placeId = ?');
        $placeQuery->execute([$adminPlaceShopPlaceId]);
        $placeRow = $placeQuery->rowCount();

        //Si le lieu existe
        if ($placeRow == 1) 
        {
            //On fait une requête pour vérifier si le magasin choisit existe
            $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
            WHERE shopId = ?');
            $shopQuery->execute([$adminTownShopShopId]);
            $shopRow = $shopQuery->rowCount();

            //Si le magasin existe
            if ($shopRow == 1) 
            {
                //On fait une requête pour vérifier si le magasin n'est pas déjà dans cette lieu
                $placeShopQuery = $bdd->prepare('SELECT * FROM car_places_shops 
                WHERE placeShopplaceId = ?
                AND placeShopShopId = ?');
                $placeShopQuery->execute([$adminPlaceShopPlaceId, $adminTownShopShopId]);
                $placeShopRow = $placeShopQuery->rowCount();

                //Si le magasin est dans le lieu
                if ($placeShopRow == 1) 
                {
                    //On supprime l'équipement de la base de donnée
                    $placeShopDeleteQuery = $bdd->prepare("DELETE FROM car_places_shops
                    WHERE placeShopShopId = ?");
                    $placeShopDeleteQuery->execute([$adminTownShopShopId]);
                    $placeShopDeleteQuery->closeCursor();
                    ?>

                    Le magasin a bien été retiré du lieu

                    <hr>
                        
                    <form method="POST" action="managePlaceShop.php">
                        <input type="hidden" name="adminPlaceShopPlaceId" value="<?php echo $adminPlaceShopPlaceId ?>">
                        <input type="submit" class="btn btn-default form-control" name="manage" value="Continuer">
                    </form>
                    
                    <?php
                }
                //Si le magasin n'est pas dans le lieu disponible
                else
                {
                    echo "Erreur : Ce magasin n'est pas dans cette lieu";
                }
                $placeShopQuery->closeCursor();
            }
            //Si le magasin existe pas
            else
            {
                echo "Erreur : Ce magasin n'existe pas";
            }
            $placeShopQuery->closeCursor();
        }
        //Si le lieu existe pas
        else
        {
            echo "Erreur : Cette lieu n'existe pas";
        }
        $shopQuery->closeCursor();
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
    echo "Erreur : Tous les champs n'ont pas été remplis";
}

require_once("../html/footer.php");