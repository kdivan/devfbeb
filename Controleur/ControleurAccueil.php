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
        session_start();
        //phpinfo();
        //echo "<pre>";
        $redirectLink = SERVER_NAME ;
        $logMessage = "";
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $file_name = "";
        $redirectUrl = $redirectLink;
        $helper = new FacebookRedirectLoginHelper($redirectUrl);
        $_SESSION['helper'] = $helper;
        /*if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            $session = new FacebookSession($_SESSION['fb_token']);
        } else {
            echo "<br>else<br>";
            $session = $helper->getSessionFromRedirect();
            $_SESSION['session'] = $session;
        }*/
        //TODO : GET CURRRENT USER SESSION
        /*$user = "";
        if ($session) {
            echo "ifsession";
            try {
                $token = (String)$session->getToken();
                $_SESSION['fb_token'] = $token;
            } catch (\Facebook\FacebookAuthorizationException $e) {
                echo "catch ".$e->getMessage();
                exit;
                $logMessage = $e->getMessage();
                $helper = new FacebookRedirectLoginHelper($redirectUrl);
                $auth_url = $helper->getLoginUrl();
                //$redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
            }
        }else{
            $logMessage = "";
            $helper = new FacebookRedirectLoginHelper($redirectUrl);
            $auth_url = $helper->getLoginUrl();
            $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
        }*/
        //if($session){
            $this->genererVue( );
        /*}
        else{
            $this->genererVue( array( 'redirectLink'=> $redirectLink,'logMessage'=>$logMessage,"user"=>$user),false );
        }*/
    }
}

