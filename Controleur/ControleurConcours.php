<?php
error_reporting(E_ALL);
ini_set("error_display",1);

require_once "Config/config.inc.php";
require_once 'Framework/Controleur.php';
require_once "Contenu/facebook/facebook-php-sdk-v4-4.0-dev/autoload.php";
require_once "Modele/FacebookFunctions.php";
require_once "Modele/Utilisateur.php";
require_once "Modele/Concours.php";
require_once "Modele/Participation.php";

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRedirectLoginHelper;


class ControleurConcours extends Controleur{


    private $concours;
    private $fb;
    private $session;

    public function __construct() {
        $this->concours     = new Concours();
    }

    /**
     *
     */
    public function index(){
        if(!isset($_SESSION)){
            session_start();
        }
        $this->fb = new FacebookFunctions($_SESSION);
        $this->executerAction("resultat");
    }

    /**
     *
     */
    public function resultat(){
        $participation = new Participation();
        $allParticipation = $participation->getParticpationFromCurrentConcours();
        foreach($allParticipation as $part){
            $participationData[]    = array_merge($part,$this->fb->getFbStats(SERVER_NAME.'/photo/participation/'.$part['facebook_photo_id']));
        }
        $winnersArray = $this->array_sort($participationData,'like_count',SORT_DESC,3);
        $concoursPrize = $this->concours->getConcoursPrize();
        $this->genererVue(array('winnersArray'=>$winnersArray,'concoursPrize'=>$concoursPrize));
    }
}