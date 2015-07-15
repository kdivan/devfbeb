<?php

require_once 'Framework/Controleur.php';
require_once 'Modele/FacebookFunctions.php';
require_once 'Modele/Utilisateur.php';
require_once 'Modele/Concours.php';
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
    private $utilisateur;
    private $concours;

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
        $this->utilisateur = new Utilisateur();
        $this->concours = new Concours();
    }

    /**
     * Génère la vue index
     * @sendDataToView Array contenant les albums de l'utilisateur
     */
    public function index() {
        $allParticipation = $this->participation->getParticpationFromCurrentConcours();
        foreach($allParticipation as $part){
            $fbPhotoInfo            = $this->fb->getPictureInfo($part['facebook_photo_id'],'http://devfbeb1.herokuapp.com/photo/participation/'.$part['id_participation']);
            $participationData[]    = array_merge($part,$fbPhotoInfo);
        }
        //var_dump($this->concours->getConcoursPrize());
        echo "<pre>";
        print_r($this->array_sort($participationData,'like_count',SORT_DESC,3));
        var_dump($this->concours->getConcoursPrize());
        //$pictInfo = $this->fb->getPictureInfo("10153408405008972",'http://devfbeb1.herokuapp.com/photo/participation/30');
        //var_dump($pictInfo);
        $userArray = [];
        /*$insertUserArray['facebook_id']     = "111111111111";
        $insertUserArray['facebook_link']   = "https://www.facebook.com/app_scoped_user_id/834538663296175/";
        $insertUserArray['nom']             = "Test";
        $insertUserArray['prenom']          = "Test";
        $insertUserArray['genre']           = "M";
        $insertUserArray['localisation']    = "fr_FR";
        $insertUserArray['email']           = "test@live.fr";
        $lastInsertId = $this->utilisateur->insert(DB_PREFIX.'utilisateurs',$insertUserArray);
        var_dump($lastInsertId);*/
        exit;
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

