<?php
error_reporting(E_ALL);
ini_set("error_display",1);

require_once "Config/config.inc.php";
require_once 'Framework/Controleur.php';
require_once "Contenu/facebook/facebook-php-sdk-v4-4.0-dev/autoload.php";
require_once "Modele/FacebookFunctions.php";
require_once "Modele/Utilisateur.php";
require_once "Modele/Concours.php";

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRedirectLoginHelper;


class ControleurAccueil extends Controleur {

    private $utilisateur;
    private $concours;
    private $fb;

    public function __construct() {
        $this->utilisateur  = new Utilisateur();
        $this->concours     = new Concours();
    }

    /**
     *
     */
    public function index(){
        if(!isset($_SESSION)){
            session_start();
        }
        $redirectUrl = SERVER_NAME ;
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $helper = new FacebookRedirectLoginHelper($redirectUrl);
        $_SESSION['helper'] = $helper;
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            $session = new FacebookSession($_SESSION['fb_token']);
        } else {
            $session = $helper->getSessionFromRedirect();
            $_SESSION['session'] = $session;
        }
        if ($session) {
            $token = (String)$session->getAccessToken();
            $_SESSION['fb_token'] = $token;
            //$this->redirect(SERVER_NAME."photo/");
        }
        //controle si concours non fini
        if( $this->concours->isCurrentConcoursFinished() ){
            $this->fb = new FacebookFunctions($_SESSION);
            $this->executerAction("resultat");
        } else {
            //TODO : GET CURRRENT USER SESSION
            $this->genererVue( );
        }
    }

    /**
     *
     */
    public function resultat(){
        $participation = new Participation();
        $allParticipation = $participation->getParticpationFromCurrentConcours();
        foreach($allParticipation as $part){
            $participationData[]    = array_merge($part,$this->fb->getFbStats('https://devfbeb1.herokuapp.com/photo/participation/'.$part['facebook_photo_id']));
        }
        $winnersArray = $this->array_sort($participationData,'like_count',SORT_DESC,3);
        //var_dump($winnersArray);
        $concoursPrize = $this->concours->getConcoursPrize();
        $this->genererVue(array('winnersArray'=>$winnersArray,'concoursPrize'=>$concoursPrize));
    }



}

