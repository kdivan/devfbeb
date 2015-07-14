<?php
error_reporting(E_ALL);
ini_set("error_display",1);

require_once "Config/config.inc.php";
require_once 'Framework/Controleur.php';
require_once "Contenu/facebook/facebook-php-sdk-v4-4.0-dev/autoload.php";
require_once "Modele/FacebookFunctions.php";
require_once "Modele/Utilisateur.php";

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRedirectLoginHelper;


class ControleurAccueil extends Controleur {

    private $utilisateur;

    public function __construct() {
        $this->utilisateur = new Utilisateur();
    }

    /**
     *
     */
    public function index(){
        if(!isset($_SESSION)){
            session_start();
        }
        $redirectLink = SERVER_NAME ;
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $redirectUrl = $redirectLink;
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
        //TODO : GET CURRRENT USER SESSION
        $this->genererVue( );
    }
}

