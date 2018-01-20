<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//Si les variables $_POST suivantes existent
if (isset($_POST['adminPlaceId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminPlaceId'])
    && $_POST['adminPlaceId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminPlaceId = htmlspecialchars(addslashes($_POST['adminPlaceId']));

        //On fait une requête pour vérifier si l'objet choisit existe
        $placeQuery = $bdd->prepare('SELECT * FROM car_places 
        WHERE placeId = ?');
        $placeQuery->execute([$adminPlaceId]);
        $placeRow = $placeQuery->rowCount();

        //Si le lieu existe
        if ($placeRow == 1) 
        {
            //On fait une recherche dans la base de donnée de toutes les lieux
            while ($place = $placeQuery->fetch())
            {
                $adminplaceName = stripslashes($place['placeName']);
            }
            ?>
            
            Que souhaitez-vous faire du lieu <em><?php echo $adminplaceName ?></em> ?

            <hr>
                
            <form method="POST" action="editPlace.php">
                <input type="hidden" class="btn btn-default form-control" name="adminPlaceId" value="<?php echo $adminPlaceId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier le lieu">
            </form>
            <form method="POST" action="../placesShops/managePlaceShop.php">
                <input type="hidden" class="btn btn-default form-control" name="adminplaceShopPlaceId" value="<?php echo $adminPlaceId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Magasins du lieu">
            </form>
            <form method="POST" action="../placesMonsters/managePlaceMonster.php">
                <input type="hidden" class="btn btn-default form-control" name="adminplaceMonsterPlaceId" value="<?php echo $adminPlaceId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Monstres du lieu">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le lieu n'exite pas
        else
        {
            echo "Erreur : Cette lieu n'existe pas";
        }
        $placeQuery->closeCursor();
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