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
        $redirectUrl = SERVER_NAME;
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $helper = new FacebookRedirectLoginHelper( $redirectUrl );
        $_SESSION['helper'] = $helper;
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            $session = new FacebookSession($_SESSION['fb_token']);
        } else {
            $session = $helper->getSessionFromRedirect();
            $_SESSION['session'] = $session;
        }
        if ($session) {
            $this->session = $session;
            $token = (String)$session->getAccessToken();
            $_SESSION['fb_token'] = $token;
        } else {
            $logMessage = "else";
            $helper = new FacebookRedirectLoginHelper( $redirectUrl );
            $auth_url = $helper->getLoginUrl();
            $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
        }
        if (!$session) {
            $this->genererVue(array('redirectLink' => $redirectLink) );
        }else{
            $this->fb = new FacebookFunctions($session);
        }
        //controle si concours non fini
        if( !$this->concours->isCurrentConcoursFinished() ){
            $this->redirect(SERVER_NAME);
        } else {
            $this->executerAction("resultat");
        }
    }

    /**
     *
     */
    public function resultat(){
        $participation = new Participation();
        $allParticipation = $participation->getParticpationFromCurrentConcours();
        var_dump($allParticipation);
        foreach($allParticipation as $part){
            $fbPhotoInfo            = $this->fb->getPictureInfo($part['facebook_photo_id'],'http://devfbeb1.herokuapp.com/photo/participation/'.$part['id_participation']);
            $participationData[]    = array_merge($part,$fbPhotoInfo);
        }
        /*$winnersArray = $this->array_sort($participationData,'like_count',SORT_DESC,3);
        $concoursPrize = $this->concours->getConcoursPrize();
        $this->genererVue(array('winnersArray'=>$winnersArray,'concoursPrize'=>$concoursPrize));*/
    }
}