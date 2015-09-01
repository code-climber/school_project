<?php

namespace ecole\controller;

use ecole\model\dao\DBOperation;
use ecole\model\dao\EleveManager;
use ecole\model\dao\ClasseManager;
use ecole\model\dao\NoteManager;
use ecole\model\dao\MatiereManager;
use ecole\model\Eleve;
use ecole\model\Note;

//    use ecole\model\Classroom;


class Controller {

    public function __construct() {
        $this->exec();
    }

    private function exec() {
        $sPage = 'home';
        if (array_key_exists('page', $_GET)) {
            $sPage = $_GET['page'];
        }

        require ROOT . 'inc/site.header.inc.php';

        $sFunction = 'handle' . ucfirst($sPage);
        // check if function exists in the current class :
        if (method_exists($this, $sFunction)) {
            // call the function
            $this->$sFunction();
        } else {
            $this->handleHome();
        }
        require ROOT . 'inc/site.footer.inc.php';
    }

    private function handleHome() {
        $aEleves = EleveManager::getAllKids();
        require ROOT . 'src/ecole/view/home.php';
    }

    //Méthode permettant d'afficher le profil d'un élève, de créer un élève ou de modifier ses infos.
    private function handleEleve() {

        //déclaration
        $idEleve = $nom = $prenom = $age = $sexe = $classe = $classeId = $error = NULL;

        //passage des classes et des matieres au formulaire pour les selects
        $aClasses = ClasseManager::getAllClassesObject();
        $aMatieres = MatiereManager::getAllMatieres();


        //Si en mode d'update, il y aura une clé idEleve dans $_GET et on fera passer les infos
        //de l'élève en cours via $_GET au formulaire d'élève.
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
            
        }
        //en update ou create, l'envoi se fera en $_POST, il faut vérifier les données.
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
//                $oEleve->setClasse($classe);
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

    //Fonction permettant de supprimer un élève particulier
    private function handleDeleteOneEleve() {
        //récupérer l'id de l'élève à supprimer
        $idEleve = array_key_exists('idEleve', $_GET) ? $_GET['idEleve'] : false;

        $oEleve = EleveManager::getOneKid($idEleve);
        EleveManager::deleteEleve($oEleve);

        $this->handleHome();
        return;
    }

    //fonction permettant d'ajouter une note pour une matière et pour un élève.
    private function handleAddNote() {
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

    //Fonction pour formatter les données avant hydratation d'un objet
    private function test_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

}
