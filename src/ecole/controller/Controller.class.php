<?php

namespace ecole\controller;

use ecole\model\dao\EleveManager;
use ecole\model\dao\ClasseManager;
use ecole\model\dao\NoteManager;
use ecole\model\dao\MatiereManager;
use ecole\model\dao\UserManager;
use ecole\model\Eleve;
use ecole\model\Note;
use ecole\model\User;

class Controller {

    /***************************************************************************
     * GESTION DE L AFFICHAGE DES PAGES EN FONCTION DE L URL
     */
    public function __construct() {
        $this->exec();
    }

    private function exec() {
        $sPage = 'home';
        if (array_key_exists('page', $_GET)) {
            $sPage = $_GET['page'];
        }
        //notion de tamporisation de sortie pour le rafraichissement de page.
        ob_start();/* initialisation du tampon: on y stocke tout le contenu à renvoyer au client. */
        require ROOT . 'inc/site.header.inc.php';

        $sFunction = 'handle' . ucfirst($sPage);
        // Recherche si la méthode existe dans la class courrante :
        if (method_exists($this, $sFunction)) {
            // call the function
            $this->$sFunction();
        } else {
            $this->handleHome();
        }
        require ROOT . 'inc/site.footer.inc.php';
        ob_flush(); /* On vide le tampon et on retourne le contenu au client. */
    }

    /***************************************************************************
     * GESTION DES FONCTIONS DE CRUD
     */
    
    
    /*
     * Méthode pour AFFICHER tous les élèves sur la page d'accueil.
     */
    private function handleHome() {
        if(array_key_exists('search', $_POST)){
            if(!empty($_POST['search']) && is_string($_POST['search']) == true ){
                $eleveName = htmlentities($_POST['search']);
                $aEleves = EleveManager::getChosenKids($eleveName);
//                var_dump($aEleves);die();
            }  
        }else{
            $aEleves = EleveManager::getAllKids();
//            var_dump($aEleves);die();
        } 
        require ROOT . 'src/ecole/view/home.php';
    }

    /* HANDLE ELEVE
     * Méthode permettant de gérer les informations d'UN élève :
     * Affichage, modification, création
     */
    private function handleEleve() {

        //déclaration des variables
        $idEleve = $nom = $prenom = $age = $sexe = $classe = $classeId = $error = NULL;

        //passage des classes et des matieres au formulaire pour les listes déroulantes
        $aClasses = ClasseManager::getAllClassesObject();
        $aMatieres = MatiereManager::getAllMatieres();

        /*
         * Si en mode d'update ou d'affichage, il y aura une clé idEleve dans $_GET et on fera passer les infos
         * de l'élève en cours via $_GET au formulaire d'élève.
         */
        
        if (array_key_exists('idEleve', $_GET)) {
            $idEleve = isset($_GET['idEleve']) ? intval($_GET['idEleve']) : "";
            $oEleve = EleveManager::getOneKid($idEleve);
            $nom = $oEleve->getNom();
            $prenom = $oEleve->getPrenom();
            $age = $oEleve->getAge();
            $sexe = $oEleve->getSexe();
            $classe = $oEleve->getClasse();
            $classeId = $oEleve->getClasseId();

            //passage des notes par matière concernant un élève
            $aNotes = NoteManager::getAllNotesByEleve($idEleve);
            
            //passage de la moyenne générale de l'élève
            $totalAvg = NoteManager::getTotalAvgByEleve($idEleve);
        }
        /*
         * en update ou create, l'envoie se fera en $_POST, il faut vérifier les données.
         * A FAIRE EVOLUER AVEC DES REGEX.
         */
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            
            if (empty($_POST['idEleve'])) {
                $idEleve = NULL;
            } else {
                $idEleve = intval($_POST['idEleve']);
            }

            //vérification du nom
            if (empty($_POST['nom'])) {
                $error = "Il manque un nom.";
            } else {
                $nom = $this->test_input($_POST['nom']);
            }

            //vérification du prénom
            if (empty($_POST['prenom'])) {
                $error = "Il manque un prénom";
            } else {
                $prenom = $this->test_input($_POST['prenom']);
            }

            //vérification de l'âge
            if (empty($_POST['age'])) {
                $error = "Il manque l'age.";
            } elseif ($_POST['age'] < 5 || $_POST['age'] > 10) {
                $error = "L'âge doit être un entier compris entre 5 et 10 ans.";
            } else {
                $age = $this->test_input($_POST['age']);
                $age = intval($age);
            }

            //vérification du sexe
            if (empty($_POST['sexe'])) {
                $error = "Il manque le sexe.";
            } else {
                $sexe = $this->test_input($_POST['sexe']);
            }

            //stocker l'id de la classe choisie
            $choixClasseId = intval($_POST['classe']);
            
            //Si pas d'erreur, préparation de l'objet pour envoie en bdd.
            if (empty($error)) {
                $oEleve = new Eleve();
                $oEleve->setId($idEleve);
                $oEleve->setNom($nom);
                $oEleve->setPrenom($prenom);
                $oEleve->setAge($age);
                $oEleve->setSexe($sexe);
                $oEleve->setClasseId($choixClasseId);

                if (!empty($idEleve)) {
                    EleveManager::updateEleve($oEleve);
                } else {
                    EleveManager::addEleve($oEleve);
                }
                
                $this->handleHome();
                return;
            }
        }
        require ROOT . 'src/ecole/view/fiche_eleve.php';
    }

    /*
     * Méthode de SUPPRESSION : supprimer UN élève particulier
     */
    private function handleDeleteOneEleve() {
        //Uniquement possible par l'admin
        if (empty($_SESSION["role"])) {
            $this->handleLogin();
            return;
        }
        //récupérer l'id de l'élève à supprimer
        $idEleve = array_key_exists('idEleve', $_GET) ? $_GET['idEleve'] : false;

        $oEleve = EleveManager::getOneKid($idEleve);
        EleveManager::deleteEleve($oEleve);

        $this->handleHome();
        return;
    }
    
    /*
     * Méthode d'AJOUT : ajouter une note pour une matière et pour un élève.
     */
    private function handleAddNote() {
        //Uniquement possible par l'admin
        if (empty($_SESSION["role"])) {
            $this->handleLogin();
            return;
        }
        
        //déclaration
        $errorNote = $note = $matiere = NULL;

        //récupérer l'id de l'élève pour lequel ajouter une note.
        $idEleve = array_key_exists('idEleve', $_GET) ? intval($_GET['idEleve']) : "";

        //Traiter les données du formulaire.
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (empty($_POST['idEleve'])) {
                $idEleve = NULL;
            } else {
                $idEleve = intval($_POST['idEleve']);
            }

            //vérification de la matière
            if (empty($_POST['matiere'])) {
                $errorNote = "Veuillez choisir une matière.";
            } else {
                $matiere = $this->test_input($_POST['matiere']);
            }

            //vérification de la note.
            if (empty($_POST['note'])) {
                $errorNote = "Veuillez choisir une note.";
            } else {
                $note = $this->test_input($_POST['note']);
            }
            
            if(!$errorNote){
                $oNote = new Note();
                $oNote->setNote($note);
                $oNote->setEleve_id($idEleve);
                $oNote->setMatiere_id($matiere);
                
                NoteManager::addNote($oNote);
                
            }
            
            $urlParams = array('page'=>'Eleve','idEleve'=>$idEleve);
            $urlParams = http_build_query($urlParams,'',"&");
            $url = "http://localhost/3WA/developpement/php/ecole_poo/ecole/index.php?".$urlParams;

            header("LOCATION: $url");

            
            return;
        }
    }

    /*
     * Méthode pour formatter les données avant hydratation d'un objet
     */
    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    
    /***************************************************************************
     * GESTION CONNEXION ADMINISTRATEUR
     */
    
    /*
     * 
     */
    private function performConnection(){
        //Hydratation d'un objet user par récupération des donnés du formulaire de login.
        $oUser = new User("", $_POST['login'], $_POST['password'],"");
        
        if(UserManager::connect($oUser)){
            $this->handleHome();
            //rafraichir la page d'accueil pour que se mette à jour la navbar.
            $urlParams = "page=home";
            $url = "http://localhost/3WA/developpement/php/ecole_poo/ecole/index.php?".$urlParams;
            header("LOCATION: $url");
        }else{
            $bConnectError = true;
            require ROOT . 'src/ecole/view/login.php';
        }
    }
    
    public function handleLogin(){
        if(array_key_exists("connect", $_POST)){
            $this->performConnection();
        }else{
            $bConnectError = false;
            require ROOT . 'src/ecole/view/login.php';  
        }
    }
    
    public function handleLogout() {
        UserManager::logout();
        $this->handleLogin();
        //rafraichir la page de login pour que se mette à jour la navbar.
        $urlParams = "page=login";
            $url = "http://localhost/3WA/developpement/php/ecole_poo/ecole/index.php?".$urlParams;
            header("LOCATION: $url");
        return;
    }

}
