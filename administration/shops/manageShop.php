<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//Si les variables $_POST suivantes existent
if (isset($_POST['adminShopId'])
&& isset($_POST['manage']))
{
    //On vérifie si tous les champs numérique contiennent bien un nombre entier positif
    if (ctype_digit($_POST['adminShopId'])
    && $_POST['adminShopId'] >= 1)
    {
        //On récupère l'id du formulaire précédent
        $adminShopId = htmlspecialchars(addslashes($_POST['adminShopId']));

        //On fait une requête pour vérifier si le magasin choisit existe
        $shopQuery = $bdd->prepare('SELECT * FROM car_shops 
        WHERE shopId = ?');
        $shopQuery->execute([$adminShopId]);
        $shopRow = $shopQuery->rowCount();

        //Si l'objet existe
        if ($shopRow == 1) 
        {
            //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
            while ($shop = $shopQuery->fetch())
            {
                //On récupère les informations du magasin
                $adminShopName = stripslashes($shop['shopName']);
            }
            ?>
            
            Que souhaitez-vous faire du magasin <em><?php echo $adminShopName ?></em> ?

            <hr>
                
            <form method="POST" action="editShop.php">
                <input type="hidden" class="btn btn-default form-control" name="adminShopId" value="<?php echo $adminShopId ?>">
                <input type="submit" class="btn btn-default form-control" name="edit" value="Afficher/Modifier le magasin">
            </form>
            <form method="POST" action="../shopsItems/manageShopItem.php">
                <input type="hidden" class="btn btn-default form-control" name="adminShopItemShopId" value="<?php echo $adminShopId ?>">
                <input type="submit" class="btn btn-default form-control" name="manage" value="Article(s) du magasin">
            </form>
            
            <hr>

            <form method="POST" action="index.php">
                <input type="submit" class="btn btn-default form-control" name="back" value="Retour">
            </form>
            
            <?php
        }
        //Si le magasin n'exite pas
        else
        {
            echo "Erreur : Ce magasin n'existe pas";
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