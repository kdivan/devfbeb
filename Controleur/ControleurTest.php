<?php

require_once 'Framework/Controleur.php';
require_once 'Modele/FacebookFunctions.php';
require_once 'Modele/Participation.php';
require_once "Contenu/facebook/facebook-php-sdk-v4-4.0-dev/autoload.php";

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRedirectLoginHelper;

/**
 * Class ControleurTest
 */
class ControleurTest extends Controleur {

    private $fb;
    private $session;
    private $redirectUrl;
    private $participation;

    /**
     * Constructeur
     */
    public function __construct() {
        session_start();
        $this->participation = new Participation();
        $this->setRedirectUrl(SERVER_NAME . "photo/");
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $this->setSession($this->getFacebookSession());
        $this->fb = new FacebookFunctions($this->session);
    }

    /**
     * Génère la vue index
     * @sendDataToView Array contenant les albums de l'utilisateur
     */
    public function index() {
        $albumPhotosArray = $this->fb->getAlbumPhotos('10153206206683972');
        $albumCoverArray  = $this->fb->getAlbumCoverPicture('10153206206683972');
        $coverPictureInfo = $this->fb->getPictureInfo('10153247566568972');
        $this->genererVue(array('albumPhotosArray' => $albumPhotosArray,'albumCoverArray'=>$albumCoverArray,
                                'coverPictureInfo'=>$coverPictureInfo),false);
    }


    /**
     * @return FacebookSession|null
     */
    private function getFacebookSession(){
        $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            $session = new FacebookSession($_SESSION['fb_token']);
        } else {
            $session = $helper->getSessionFromRedirect();
        }
        return $session;
    }

    /**
     * @param mixed $redirectUrl
     */
    public function setRedirectUrl($redirectUrl)
    {
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @param FacebookSession|null $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @param FacebookFunctions $fb
     */
    public function setFb($fb)
    {
        $this->fb = $fb;
    }

}

