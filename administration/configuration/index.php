<?php 
require_once("../../kernel/kernel.php");

//S'il n'y a aucune session c'est que le joueur n'est pas connecté alors on le redirige vers l'accueil
if (empty($_SESSION['account'])) { exit(header("Location: ../../index.php")); }
//Si le joueur n'a pas les droits administrateurs (Accès 2) on le redirige vers l'accueil
if ($accountAccess < 2) { exit(header("Location: ../../index.php")); }

require_once("../html/header.php");

//On fait une requête dans la base de donnée pour récupérer les informations du jeu
$configurationQuery = $bdd->query("SELECT * FROM car_configuration");

//On fait une boucle sur le ou les résultats obtenu pour récupérer les informations
while ($configuration = $configurationQuery->fetch())
{
    //On récupère les informations du jeu
    $adminGameId = stripslashes($configuration['configurationId']);
    $adminGameName = stripslashes($configuration['configurationGameName']);
    $adminGamePresentation = stripslashes($configuration['configurationPresentation']); 
    $adminGameMaxLevel = stripslashes($configuration['configurationMaxLevel']);  
    $adminGameExperience = stripslashes($configuration['configurationExperience']);
    $adminGameSkillPoint = stripslashes($configuration['configurationSkillPoint']);
    $adminGameExperienceBonus = stripslashes($configuration['configurationExperienceBonus']);
    $adminGameGoldBonus = stripslashes($configuration['configurationGoldBonus']);
    $adminGameDropBonus = stripslashes($configuration['configurationDropBonus']);
    $adminGameAccess = stripslashes($configuration['configurationAccess']);
}
$configurationQuery->closeCursor();
?>

<p>Configuration du jeu</p>

<form method="POST" action="editConfiguration.php">
    Nom du jeu : <input type="text" name="adminGameName" class="form-control" placeholder="Nom du jeu" value="<?php echo $adminGameName ?>" required>
    Présentation : <br> <textarea class="form-control" name="adminGamePresentation" id="adminGamePresentation" rows="3" required><?php echo $adminGamePresentation; ?></textarea>
    Niveau maximum : <input type="number" name="adminGameMaxLevel" class="form-control" placeholder="Niveau maximum" value="<?php echo $adminGameMaxLevel ?>" required>
    Base d'expérience (base d'expérience * niveau actuel = expérience requise pour monter de niveau) : <input type="number" name="adminGameExperience" class="form-control" placeholder="Base expérience" value="<?php echo $adminGameExperience ?>" required>
    PC par niveau : <input type="number" name="adminGameSkillPoint" class="form-control" placeholder="PC par niveau" value="<?php echo $adminGameSkillPoint ?>" required>
    Expérience bonus (%) : <input type="number" name="adminGameExperienceBonus" class="form-control" placeholder="Expérience bonus (%)" value="<?php echo $adminGameExperienceBonus ?>" required>
    Argent bonus (%) : <input type="number" name="adminGameGoldBonus" class="form-control" placeholder="Argent bonus (%)" value="<?php echo $adminGameGoldBonus ?>" required>
    Taux d'obtention bonus (%) : <input type="number" name="adminGameDropBonus" class="form-control" placeholder="Taux d'obtention bonus (%)" value="<?php echo $adminGameDropBonus ?>" required>
    Status du jeu : <select name="adminGameAccess" class="form-control">
    
        <?php
        switch ($adminGameAccess)
        {
            case "Opened":
                ?>
                <option selected="selected" value="Opened">Ouvert</option>
                <option value="Closed">Fermé</option>
                <?php
            break;
    
            case "Closed":
                ?>
                <option value="Opened">Ouvert</option>
                <option selected="selected" value="Closed">Fermé</option>
                <?php
            break;
        }
        ?>
    
    </select>
    <input type="hidden" class="btn btn-default form-control" name="token" value="<?php echo $_SESSION['token'] ?>">
    <input name="edit" class="btn btn-default form-control" type="submit" value="Modifier">
</form>

<?php require_once("../html/footer.php");