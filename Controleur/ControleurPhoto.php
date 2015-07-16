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
        $this->participation = new Participation();
        $this->utilisateur   = new Utilisateur();
        $this->concours      = new Concours();
        $this->setRedirectUrl( SERVER_NAME );
        $this->init();
        //$this->setSession($this->getFacebookSession());
        //$this->fb = new FacebookFunctions($this->session);
    }

    /**
     *
     */
    public function init(){
        if(!isset($_SESSION)){
            session_start();
        }
        //var_dump($_SESSION);
        FacebookSession::setDefaultApplication(FB_APPID, FB_APPSECRET);
        $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
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
            $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
            $auth_url = $helper->getLoginUrl([FB_RIGHTS]);
            $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
        }
        if (!$session) {
            echo "session generer vue";
            $this->genererVue(array('redirectLink' => $redirectLink),true,"index");
        }else{
            $this->fb = new FacebookFunctions($session);
        }
    }

    /**
     *
     * Génère la vue index
     * @sendDataToView Array contenant les albums de l'utilisateur
     */
    public function index($errorMessage=NULL) {
        //check if user has all perms
        if ($this->fb->checkPerms(array('public_profile', 'email', 'user_photos', 'publish_actions'))) {
            try {
                $message = "";
                $currentUser = $this->fb->getCurrentUser();
                //Vérification dans la table utilisateur
                $localUser = $this->utilisateur->getUtilisateur(array('facebook_id', $currentUser->getId()));
                if (!$localUser) {
                    //Insertion en base
                    $this->utilisateur->insertUtilisateur($currentUser);
                }
                //check if user has already participate in the competition
                $participation = $this->participation->hasUserParticipateCurrentConcours($localUser['id']);
                $participation = false;
                if ($participation) {
                    //si le paramètre id existe => mode modification
                    // mettre l'image actuel en preview, pré remplir le message
                    if ( $this->requete->existeParametre('id') ) {
                        //recupération des infos de participation
                        $albumsArray        = $this->getAlbumData();
                        $participationId    = $this->requete->getParametre('id');
                        //controle avec current user
                        //isUserParticipation($participationId,$localUser);
                        $participation      = $this->participation->findBy( array("id"=>$participationId) );
                        if( $participation['fk_utilisateur_id'] == $localUser['id']) {
                            $fbPhotoInfo        = $this->fb->getPictureInfo($participation['facebook_photo_id']);
                            $participationDataArray[]  = array_merge($participation,$fbPhotoInfo);
                            $this->genererVue(array('editParticipation' => 'true', 'participation' => $participation,
                                                'albumsArray' => $albumsArray, 'participationDataArray'=> $participationDataArray), false);
                        } else {
                            $this->genererVue(array('notAllowed' => true) ) ;
                        }
                    } else {
                        $this->executerAction( 'participate', array('hasParticipate' => true, 'participation' => $participation) );
                    }
                }
                else {
                    $albumsArray = $this->getAlbumData();
                    if (isset($errorMessage)) {
                        $this->genererVue(array('albumsArray' => $albumsArray, 'errorMessage' => $errorMessage));
                    } else {
                        $this->genererVue(array('albumsArray' => $albumsArray));
                    }
                }
            } catch (Exception $e) {
                var_dump($e);
                exit;
            }
        } //redirect for login with perms
        else {
            $helper = new FacebookRedirectLoginHelper($this->redirectUrl);
            $auth_url = $helper->getLoginUrl([FB_RIGHTS]);
            $redirectLink = "<script>window.top.location.href='" . $auth_url . "'</script>";
            $this->genererVue(array('redirectUrl' => $redirectLink));
        }

    }

    /**
     * @return array
     */
    private function getAlbumData(){
        $userAlbumArray = $this->fb->getUserAlbums();
        foreach ($userAlbumArray['data'] as $album) {
            $albumArray = json_decode(json_encode($album), true);
            if (array_key_exists('cover_photo', $albumArray)) {
                try {
                    $coverInfo = $this->fb->getPictureInfo($albumArray['cover_photo']);
                } catch (Exception $e) {
                    $e->getMessage();
                    //exit;
                }
                $albumsArray[] = array_merge($coverInfo, $albumArray);
            }
        }
        return $albumsArray;
    }

    /**
     * @param $dataArray
     */
    public function participate($dataArray) {
        $this->genererVue( $dataArray );
    }

    /**
     * AJAX - Fonction appelé par AJAX
     * Récupère les photos associés aux albums et génère la vue
     * @sendDataToView Array contenant les photos associées aux albums de l'utilisateur
     */
    public function getphotos(){
        if ( $this->requete->existeParametre('albumId') ) {
            $this->fb = new FacebookFunctions($this->session);
            $albumId = $this->requete->getParametre('albumId');
            $albumPhotosArray = $this->fb->getAlbumPhotos($albumId);
            $this->genererVue( array('albumPhotosArray' => $albumPhotosArray),false );
        }
    }

    /**
     *
     */
    public function participation(){
        $this->init();
        if ( $this->requete->existeParametre('id') ) {
            $participationId    = $this->requete->getParametre('id');
            $participation      = $this->participation->findBy( array("id"=>$participationId) );
            $fbPhotoInfo        = $this->fb->getPictureInfo($participation['facebook_photo_id'],SERVER_NAME.'photo/participation/'.$participation['id_participation']);
            $participationDataArray[]  = array_merge($participation,$fbPhotoInfo);
            $this->genererVue( array('participationDataArray' => $participationDataArray[0]) );
        }
    }

    /**
     * AJAX - Fonction appelé par AJAX
     * Récupère les photos associés aux albums et génère la vue
     * @sendDataToView Array contenant les photos associées aux albums de l'utilisateur
     */
    public function getparticipationdetail(){
        $this->init();
        if ( $this->requete->existeParametre('participationId') ) {
            $participationId    = $this->requete->getParametre('participationId');
            $participation      = $this->participation->findBy( array("id"=>$participationId) );
            $fbPhotoInfo        = $this->fb->getPictureInfo($participation['facebook_photo_id'],SERVER_NAME.'photo/participation/'.$participation['id_participation']);
            $participationDataArray[]  = array_merge($participation,$fbPhotoInfo);
            $this->genererVue( array('participationDataArray' => $participationDataArray[0]),false );
        }
    }

    /**
     *
     */
    public function getmoreparticipation(){
        $this->init();
        if( $this->requete->existeParametre('filter') ) {
            $selectedFilter = $this->requete->getParametre('filter');
        } else {
            $selectedFilter = "more_recent";
        }
        $participationData      = [];
        $limitMin               = $this->requete->getParametre('limitMin');
        $limitMax               = $this->requete->getParametre('limitMax');
        $participationList      = $this->participation->getParticipationWithLimit($limitMin,$limitMax,$selectedFilter);
        $nbElem                 = $limitMax - $limitMin;
        $class                  = (count($participationList)<=$nbElem)?"disable":"enable";
        foreach($participationList as $participation){
            $fbPhotoInfo            = $this->fb->getPictureInfo($participation['facebook_photo_id'],SERVER_NAME.'photo/participation/'.$participation['id_participation']);
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
    public function participer( ){
        //me/permission permettent de recupérer les permissions données par l'utilisateur
        $errorMessage = "";
        $editMode = null;
        $lastId = "";
        try{
            if ( $this->session ) {
                if( $this->requete->existeParametre('submit') ) {
                    $userMessage = "Votez pour moi !";
                    if ($this->requete->existeParametre('message')) {
                        if (strlen($this->requete->getParametre('message')) > 0) {
                            $userMessage = $this->requete->getParametre('message');
                        }
                    }
                    //echo "message de l'utilisateur " . $userMessage;
                    if( $this->requete->existeParametre('photo_from') ){
                        $dataToInsert = [];
                        if( ( trim($this->requete->getParametre('photo_from'))=='fb' ) ){
                            $fbPhotoId    =  $this->requete->getParametre('photo_facebook_id') ;
                        }elseif( trim($this->requete->getParametre('photo_from'))=='local' ){
                            if ( $_FILES['fichier']['error'] > 0 ){
                                throw new Exception("Erreur lors du transfert : ". $_FILES['fichier']['error']);
                            }else {
                                try {
                                    //Upload l'image sur le mur de l'utilisateur
                                    $this->fb = new FacebookFunctions($this->session);
                                    $response = $this->fb->uploadPhotoToUserTimeline($_FILES, $userMessage);
                                    $fbPhotoId = $response->getProperty('id');
                                } catch (FacebookRequestException $e) {
                                    $errorMessage = $e->getMessage();
                                }
                            }
                        }else{
                            throw new Exception("Action non reconnue : ni local, ni fb");
                        }
                        if( strlen($errorMessage) < 1 ){
                            $currentUser = $this->fb->getCurrentUser();
                            $localUser = $this->utilisateur->getUtilisateur(array('facebook_id',$currentUser->getId()));

                            $dataToInsert['facebook_photo_id']  = $fbPhotoId;
                            $dataToInsert['fk_utilisateur_id']  = $localUser['id'];
                            $dataToInsert['fk_concours_id']     = $this->concours->getIdConcours();
                            $dataToInsert['message']            = $userMessage;
                            $dataToInsert['actif']              = 1;
                            $dataToInsert['date_participation'] = date('Y-m-d H:i:s');
                            $lastId  = $this->participation->insertParticipation($dataToInsert);

                            //si modification de l'image
                            if( $this->requete->existeParametre('edit_mode') ){
                                if( trim($this->requete->getParametre('edit_mode'))=='true' ){
                                    $editMode = "true";
                                    $this->participation->disableParticipation($this->requete->getParametre('id_participation'));
                                }
                            }
                        }
                    }else{
                        throw new Exception("Action non reconnue : Photo from null");
                    }
                }else{
                    throw new Exception("Une erreur a eu lieu lors de l'upload de la photo");
                }
            }else{
                $this->setSession( $this->getFacebookSession() );
            }
        }catch (Exception $e){
            $errorMessage = $e->getMessage();
            //$errorMessage  = "test";
        }
        //$this->genererVue(array("message"=>$errorMessage,'param'=>$this->requete->getParametre('edit_mode'),'return'=>$lastId));
        //Genere la vue en fonction des evenements
        if( strlen($errorMessage) > 0 ){
            $this->genererVue(array('errorMessage'=>$errorMessage),true,"index");
        }else{
            $this->executerAction("confirmation",array( 'lastId'=>$lastId, 'editMode'=>$editMode ));
        }
    }

    /**
     *
     */
    public function confirmation($params=array()){
        $this->genererVue($params);
    }

    /**
     *
     */
    public function gallery() {
        $this->init();
        $filterArray = [];
        $filterArray[0]['filter_val'] = "more_recent";
        $filterArray[0]['filter_string'] = "Les plus récentes";
        $filterArray[1]['filter_val'] = "less_recent";
        $filterArray[1]['filter_string'] = "Les moins récentes";
        $filterArray[2]['filter_val'] = "more_vote";
        $filterArray[2]['filter_string'] = "Les plus votées";
        if( $this->requete->existeParametre('filter') ) {
            $withGabarit = false;
            $selectedFilter = $this->requete->getParametre('filter');
        } else {
            $withGabarit = true;
            $selectedFilter = "more_recent";
        }
        $cpt                = 0;
        $photosDataArray    = [];
        $limitMax           = MAX_IMAGE_PER_LINE*2;
        //+1 pour vérifier qu'il y a au moins un element en plus pour charger la suite
        $photosGalleryArray = $this->participation->getParticipationWithLimit(0,$limitMax+1, $selectedFilter);
        $class              = (count($photosGalleryArray)<$limitMax+1)?"disable":"enable";
        if (count($photosGalleryArray)<$limitMax+1){
            $class          = "disable";
            $elementLoad    = count($photosGalleryArray);
        }else{
            $class          = "enable";
            $elementLoad    = $limitMax;
        }
        $currentUser = $this->fb->getCurrentUser();
        //Vérification dans la table utilisateur
        $localUser = $this->utilisateur->getUtilisateur(array('facebook_id', $currentUser->getId()));
        $participation = $this->participation->hasUserParticipateCurrentConcours($localUser['id']);
        $hasParticipate = ( isset( $participation ) )? true : false;
        foreach($photosGalleryArray as $photos){
            $cpt++;
            if($cpt<=$limitMax) {
                $fbPhotoInfo = $this->fb->getPictureInfo($photos['facebook_photo_id'],SERVER_NAME.'photo/participation/'.$photos['id_participation']);
                $photosDataArray[] = array_merge($photos, $fbPhotoInfo);
            }
        }
        $this->genererVue( array( 'photosGalleryArray'=>$photosDataArray,'class'=>$class,'elementLoad'=>$elementLoad,
                                    'hasParticipate' => $hasParticipate, 'participation'=>$participation, 'filterArray'=>$filterArray,
                                    'selectedFilter' => $selectedFilter ),$withGabarit ) ;
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

