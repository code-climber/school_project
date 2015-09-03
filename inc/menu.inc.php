<?php
    //use ecommerce\model\Category;
    //use ecommerce\model\dao\CategoryManager;

?>
<nav class="navbar navbar-default" role="navigation">
    <ul class="list-inline">
        <li>
            <a href="<?php echo 'index.php?page=home'; ?>" class="btn btn-default">Accueil</a>
            
            <?php if(!array_key_exists('role', $_SESSION)): ?>
            <a href="<?php echo 'index.php?page=login'; ?>" class="btn btn-default">Connexion administrateur</a>
            <?php endif; ?>
            
            <?php if(array_key_exists('role', $_SESSION)): ?>
            <a href="index.php?page=Eleve" class="btn btn-default">Ajouter un élève</a>
            <?php endif; ?>
            
            <?php if(array_key_exists('role', $_SESSION)): ?>
            <a href="index.php?page=logout" class="btn btn-default">Se déconnecter</a>
            <?php endif; ?>
        </li>
    </ul>    
</nav>