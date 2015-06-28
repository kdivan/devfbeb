<?php

require_once 'Framework/Controleur.php';
require_once 'Modele/FacebookFunctions.php';
require_once 'Modele/Participation.php';
require_once 'Modele/Utilisateur.php';
require_once 'Modele/Concours.php';
require_once "Contenu/facebook/facebook-php-sdk-v4-4.0-dev/autoload.php";

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRedirectLoginHelper;

/**
 * Class ControleurPhoto
 */
class ControleurPhoto extends Controleur {

    /**
     * @var FacebookFunctions
     */
    private $fb;
    /**
     * @var
     */
    private $session;
    /**
     * @var
     */
    private $redirectUrl;
    /**
     * @var Participation
     */
    private $participation;
    /**
     * @var Utilisateur
     */
    private $utilisateur;
    /**
     * @var Concours
     */
    private $concours;

    /**
     * Constructeur
     */
    public function __construct() {
        session_start();
        $this->participation = new Participation();
        $this->utilisateur   = new Utilisateur();
        $this->concours      = new Concours();
        $this->setRedirectUrl(SERVER_NAME . "photo/");
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $this->setSession($this->getFacebookSession());
        $this->fb = new FacebookFunctions($this->session);
    }

    /**
     *
     */
    /*public function init(){
        //handle connexion
        $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
        $_SESSION['helper'] = $helper;
        if (isset($_SESSION) && isset($_SESSION['fb_token'])) {
            $session = new FacebookSession($_SESSION['fb_token']);
        } else {
            echo "<br>else<br>";
            $session = $helper->getSessionFromRedirect();
            $_SESSION['session'] = $session;
        }
        if ($session) {
            var_dump($session);
            $token = (String)$session->getToken();
            $_SESSION['fb_token'] = $token;
            var_dump($token);
            exit;
        } else {
            echo "else ";
            $logMessage = "";
            $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
            $auth_url = $helper->getLoginUrl([FB_RIGHTS]);
            $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
            $this->genererVue( array('redirectUrl'=>$redirectLink ) ) ;
        }
    }*/

    /**
     *
     * Génère la vue index
     * @sendDataToView Array contenant les albums de l'utilisateur
     */
    public function index($errorMessage=NULL) {
        //check if user has all perms
        if( $this->fb->checkPerms ( array ('public_profile','email','user_photos','publish_actions') ) ){
            try{
                $message="";
                $currentUser = $this->fb->getCurrentUser();
                //Vérification dans la table utilisateur
                $localUser = $this->utilisateur->getUtilisateur(array('facebook_id',$currentUser->getId()));
                if(!$localUser){
                    //Insertion en base
                    $this->utilisateur->insertUtilisateur($currentUser);
                }
                //check if user has already participate in the competition
                try{
                    $this->participation->hasUserParticipateCurrentConcours($localUser['id']);
                }catch ( Exception $e ){
                    $message = $e->getMessage();
                }
                if( strlen($message)>0 ){
                    $this->genererVue( array('dejaPartMessage'=>$message) ) ;
                }else{
                    $userAlbumArray = $this->fb->getUserAlbums();
                    foreach( $userAlbumArray['data'] as $album ) {
                        $albumArray = json_decode(json_encode($album), true);
                        if( array_key_exists('cover_photo',$albumArray) ) {
                            try{
                                $coverInfo = $this->fb->getPictureInfo($albumArray['cover_photo']);
                            }catch (Exception $e){
                                $e->getMessage();
                                exit;
                            }
                            $albumsArray[] = array_merge($coverInfo, $albumArray);
                        }
                    }
                    if(isset($errorMessage)){
                        $this->genererVue( array('albumsArray'=>$albumsArray,'errorMessage'=>$errorMessage ) ) ;
                    }else{
                        $this->genererVue( array('albumsArray'=>$albumsArray ) ) ;
                    }
                }
            }catch(Exception $e) {
                var_dump($e);
                exit;
            }
        }
        //redirect for login with perms
        else {
            $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
            $auth_url = $helper->getLoginUrl([FB_RIGHTS]);
            $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
            $this->genererVue( array('redirectUrl'=>$redirectLink ) ) ;
        }
    }

    /**
     * AJAX - Fonction appelé par AJAX
     * Récupère les photos associés aux albums et génère la vue
     * @sendDataToView Array contenant les photos associées aux albums de l'utilisateur
     */
    public function getphotos(){
        if ( $this->requete->existeParametre('albumId') ) {
            $albumId = $this->requete->getParametre('albumId');
            $albumPhotosArray = $this->fb->getAlbumPhotos($albumId);
            $this->genererVue( array('albumPhotosArray' => $albumPhotosArray),false );
        }
    }

    /**
     * AJAX - Fonction appelé par AJAX
     * Récupère les photos associés aux albums et génère la vue
     * @sendDataToView Array contenant les photos associées aux albums de l'utilisateur
     */
    public function getparticipationdetail(){
        if ( $this->requete->existeParametre('participationId') ) {
            $participationId    = $this->requete->getParametre('participationId');
            $participation      = $this->participation->findBy( array("id"=>$participationId) );
            $fbPhotoInfo        = $this->fb->getPictureInfo($participation['facebook_photo_id']);
            $participationDataArray[]  = array_merge($participation,$fbPhotoInfo);
            $this->genererVue( array('participationDataArray' => $participationDataArray[0]),false );
        }
    }

    /**
     *
     */
    public function getmoreparticipation(){
        $participationData      = [];
        $limitMin               = $this->requete->getParametre('limitMin');
        $limitMax               = $this->requete->getParametre('limitMax');
        $participationList      = $this->participation->getParticipationWithLimit($limitMin,$limitMax);
        $nbElem                 = $limitMax - $limitMin;
        $class                  = (count($participationList)<=$nbElem)?"disable":"enable";
        foreach($participationList as $participation){
            $fbPhotoInfo            = $this->fb->getPictureInfo($participation['facebook_photo_id']);
            $participationData[]    = array_merge($participation,$fbPhotoInfo);
        }
        $this->genererVue( array('participationDataArray' => $participationData, 'class'=>$class, 'elementLoad'=> count($participationList) ),false );
    }

    /**
     * Upload la photo sur le mur de l'utilisateur si la photo provient de l'ordinateur
     *  $_FILES['fichier']['name'] . "<br>";     //Le nom original du fichier, comme sur le disque du visiteur (exemple : mon_icone.png).
        $_FILES['fichier']['type'] . "<br>";     //Le type du fichier. Par exemple, cela peut être « image/png ».
        $_FILES['fichier']['size'] . "<br>"; //La taille du fichier en octets.
        $_FILES['fichier']['tmp_name'] . "<br>"; //L'adresse vers le fichier uploadé dans le répertoire temporaire.
        $_FILES['fichier']['error'] . "<br>";//Le code d'erreur, qui permet de savoir si le fichier a bien été uploadé.
     * @throws \Facebook\FacebookRequestException
     */
    public function participer(){
        //me/permission permettent de recupérer les permissions données par l'utilisateur
        if ( $this->session ) {
            if( $this->requete->existeParametre('submit') ) {
                $userMessage = "Votez pour moi !";
                if ($this->requete->existeParametre('message')) {
                    if (strlen($this->requete->getParametre('message')) > 0) {
                        $userMessage = $this->requete->getParametre('message');
                    }
                }
                echo "message de l'utilisateur " . $userMessage;
                if( $this->requete->existeParametre('photo_from') ){
                    $dataToInsert = [];
                    if( ( trim($this->requete->getParametre('photo_from'))=='fb' ) ){
                        $fbPhotoId    =  $this->requete->getParametre('photo_facebook_id') ;
                    }elseif( trim($this->requete->getParametre('photo_from'))=='local' ){
                        if ( $_FILES['fichier']['error'] > 0 ){
                            $errorMessage = "Erreur lors du transfert : ". $_FILES['fichier']['error'];
                        }else{
                            try {
                                //Upload l'image sur le mur de l'utilisateur
                                $this->fb = new FacebookFunctions($this->session);
                                $response = $this->fb->uploadPhotoToUserTimeline($_FILES,$userMessage);
                                $fbPhotoId = $response->getProperty('id');
                            } catch (FacebookRequestException $e) {
                                $errorMessage =  $e->getMessage();
                            }
                            if( strlen($errorMessage) < 1 ){
                                $currentUser = $this->fb->getCurrentUser();
                                $localUser = $this->utilisateur->getUtilisateur(array('facebook_id',$currentUser->getId()));

                                $dataToInsert['facebook_photo_id']  = $fbPhotoId;
                                $dataToInsert['fk_utilisateur_id']  = $localUser['id'];
                                $dataToInsert['fk_concours_id']     = $this->concours->getIdConcours();
                                $dataToInsert['message']            = $userMessage;
                                $dataToInsert['actif']              = 1;
                                $this->participation->insertParticipation($dataToInsert);
                            }
                        }
                    }else{
                        $errorMessage = "Action non reconnue : ni local, ni fb";
                    }
                }else{
                    $errorMessage =  "Action non reconnue : Photo from null";
                }
            }else{
                $errorMessage = "Une erreur a eu lieu lors de l'upload de la photo";
            }
        }else{
            $this->setSession( $this->getFacebookSession() );
        }
        exit;
        //Genere la vue en fonction des evenements
        if( strlen($errorMessage) > 0 ){
            $this->executerAction("index",$errorMessage);
        }else{
            $this->redirect(SERVER_NAME."photo/confirmation");
        }
    }

    /**
     *
     */
    public function confirmation(){
        $this->genererVue();
    }

    /**
     *
     */
    public function gallery() {
        //TODO : getvote pour chaque photo
        $cpt                = 0;
        $photosDataArray    = [];
        $limitMax           = MAX_IMAGE_PER_LINE*2;
        //+1 pour vérifier qu'il y a au moins un element en plus pour charger la suite
        $photosGalleryArray = $this->participation->getParticipationWithLimit(0,$limitMax+1);
        $class              = (count($photosGalleryArray)<$limitMax+1)?"disable":"enable";
        if (count($photosGalleryArray)<$limitMax+1){
            $class          = "disable";
            $elementLoad    = count($photosGalleryArray);
        }else{
            $class          = "enable";
            $elementLoad    = $limitMax;
        }
        foreach($photosGalleryArray as $photos){
            $cpt++;
            if($cpt<=$limitMax) {
                $fbPhotoInfo = $this->fb->getPictureInfo($photos['facebook_photo_id']);
                $photosDataArray[] = array_merge($photos, $fbPhotoInfo);
            }
        }
        $this->genererVue( array( 'photosGalleryArray'=>$photosDataArray,'class'=>$class,'elementLoad'=>$elementLoad ) ) ;
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

