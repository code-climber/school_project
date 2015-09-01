<?php
    //démarrage d'une session
    session_start();
    
    //utilisation du controller principal
    use ecole\controller\Controller;
    
    //inclusion du fichier de configuration
    require 'inc/conf.inc.php';
    
    //instanciation d'une classe controller
    new Controller();