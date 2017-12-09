<?php 
require_once("../html/header.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

//On fait une recherche dans la base de donnée de tous les chapitres
$equipmentTypeQuery = $bdd->query("SELECT * FROM car_items_types");

?>

<form method="POST" action="editItemType.php">
    Liste des type d'objets : <select name="adminItemTypeId" class="form-control">
            
        <?php
        //On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
        while ($equipmentType = $equipmentTypeQuery->fetch())
        {
            //On récupère les informations du chapitre
            $adminItemTypeId = stripslashes($equipmentType['itemTypeId']);
            $adminItemTypeName = stripslashes($equipmentType['itemTypeName']);
            $adminItemTypeNameShow = stripslashes($equipmentType['itemTypeNameShow']);
            ?>
            <option value="<?php echo $adminItemTypeId ?>"><?php echo $adminItemTypeNameShow ?></option>
            <?php
        }
        $equipmentTypeQuery->closeCursor();
        ?>

    </select>
    <input type="submit" name="edit" class="btn btn-default form-control" value="Modifier le type de l'objet">
</form>

<?php require_once("../html/footer.php");