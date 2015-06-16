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

    // Affiche la liste de tous les billets du blog
    public function index(){
        session_start();
        //phpinfo();
        echo "<pre>";

        $redirectLink = SERVER_NAME ;
        $logMessage = "";

        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $file_name = "";
        $redirectUrl = $redirectLink;
        $helper = new FacebookRedirectLoginHelper($redirectUrl);
        $_SESSION['helper'] = $helper;
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            $session = new FacebookSession($_SESSION['fb_token']);
        } else {
            echo "<br>else<br>";
            $session = $helper->getSessionFromRedirect();
            $_SESSION['session'] = $session;
        }
        $user = "";
        if ($session) {
            echo "ifsession";
            try {
                //echo "try";
                $token = (String)$session->getAccessToken();
                //echo "token ".$token;
                $_SESSION['fb_token'] = $token;
                //prepare
                $request = new FacebookRequest($session, 'GET', '/me');
                //execute
                $response = $request->execute();

                //transform la data graphObject
                $user = $response->getGraphObject("Facebook\GraphUser");

                //Vérification dans la table utilisateur
                $result = $this->utilisateur->getUtilisateur(array('facebook_id',$user->getId()));
                echo "result ";
                var_dump($result);
                if(!$result){
                    //Insertion en base
                    $this->utilisateur->insertUtilisateur($user);
                }
            } catch (\Facebook\FacebookAuthorizationException $e) {
                $logMessage = $e->getMessage();
                var_dump($logMessage);
                $helper = new FacebookRedirectLoginHelper($redirectUrl);
                $auth_url = $helper->getLoginUrl([FB_RIGHTS]);
                //$redirectLink =  '<a href="' . $auth_url . '">Login with Facebook</a>';
                $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
            }
        }else{
            $logMessage = "else";
            $helper = new FacebookRedirectLoginHelper($redirectUrl);
            $auth_url = $helper->getLoginUrl([FB_RIGHTS]);
            //$redirectLink =  '<a href="' . $auth_url . '">Login with Facebook</a>';
            $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
        }
        if($session){
            echo "session generer vue";
            $this->genererVue(array('session' => $session,'redirectLink'=> $redirectLink,'logMessage'=>$logMessage,"user"=>$user));
        }
        else{
            echo "no session generer vue";
            $this->genererVue(array('session' => $session,'redirectLink'=> $redirectLink,'logMessage'=>$logMessage,"user"=>$user),false);
        }
    }

    public function retour()
    {
        session_start();
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $redirectLink = "http://localhost/devfbeb/Accueil/retour";
        $helper = new FacebookRedirectLoginHelper("");
        try {
            $session = $helper->getSessionFromRedirect();
        } catch (FacebookRequestException $ex) {
            var_dump($ex);
            exit;
        }
        $this->genererVue(array('session'=>$session,'helper'=>$helper));
    }

}

