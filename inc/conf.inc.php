<?php

    //définition d'une variable globale indiquant qu'on cherchera les classes
    //à partir du dossier ecole/
    define ('ROOT', realpath(dirname(__FILE__) . '/../') . '/');

    //fonction permettant d'inclure dans l'index le fichier de classe appelé en
    //paramètre.
    function autoloadItemsClass($sClassName)
    {
        $sFilePath = ROOT . 'src/' . $sClassName . '.class.php';
        if (is_file($sFilePath)) {
            require_once $sFilePath;
        }
    }

    //appel de la fonction définie ci-dessus.
    spl_autoload_register('autoloadItemsClass');