<?php

error_reporting(E_ALL);
ini_set("error_display",1);

require_once "Contenu/facebook/facebook-php-sdk-v4-4.0-dev/autoload.php";
require_once "Config/config.inc.php";

use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookRedirectLoginHelper;

/**
 * Class FacebookFunctions
 */
class FacebookFunctions
{

    var $session;

    public function __construct($session){
        $this->session = $session;
    }

    /**
     * @return mixed
     * @throws \Facebook\FacebookRequestException
     */
    public function getUserAlbums(){
        /**
         * Get all albums of the current user
         */
        try{
            //Request
            $request = new FacebookRequest($this->session,'GET','/me/albums');
            //execute
            $response = $request->execute();
            //transform la data graphObject
            $userAlbums = $response->getGraphObject();
            //$userAlbums->getProperty('data')
            $usersAlbumArray = $userAlbums->asArray();
            return $usersAlbumArray;
        }catch (Exception $e){
            var_dump($e);
            exit;
        }

    }

    /**
     * @param $albumId
     * @return mixed
     * @throws \Facebook\FacebookRequestException
     */
    public function getAlbumCoverPicture($albumId){
        //Request
        $request = new FacebookRequest($this->session, 'GET', '/' . $albumId . '/');
        //execute
        $response = $request->execute();
        //transform la data graphObject
        $albumCoverPicture = $response->getGraphObject();
        $albumCoverPictureArray = $albumCoverPicture->asArray();
        $coverPictureInfo = $this->getPictureInfo($albumCoverPictureArray['id']);
        return $coverPictureInfo;
    }

    /**
     * @param $albumId
     * @return mixed
     * @throws \Facebook\FacebookRequestException
     */
    public function getAlbumPhotos($albumId){
        $request = new FacebookRequest($this->session, 'GET', '/' . $albumId . '/photos');
        //execute
        $response = $request->execute();
        //transform la data graphObject
        $albumPicture = $response->getGraphObject();
        $albumPictureArray = $albumPicture->asArray();
        return $albumPictureArray;
    }

    /**
     * @param $fileArray
     * @param $userMessage
     * @return mixed
     * @throws \Facebook\FacebookRequestException
     */
    public function uploadPhotoToUserTimeline($fileArray, $userMessage){
        $response = (new FacebookRequest(
            $this->session, 'POST', '/me/photos', array(
                'source' => new CURLFile($fileArray['fichier']['tmp_name'], $fileArray['fichier']['type']),
                'message' => $userMessage
            )
        ))->execute()->getGraphObject();
        return $response;
    }

    /**
     * @return mixed
     * @throws \Facebook\FacebookRequestException
     */
    public function getCurrentUser(){
        $request = new FacebookRequest($this->session,'GET','/me');
        //execute
        $response = $request->execute();
        //transform la data graphObject
        $user = $response->getGraphObject("Facebook\GraphUser");
        return $user;
    }

    /**
     * @param $photoId
     * @return mixed
     * @throws \Facebook\FacebookRequestException
     */
    public function getPictureInfo($photoId){
        $token = (String)$this->session->getAccessToken();
        $_SESSION['fb_token'] = $token;
        //prepare
        $request = new FacebookRequest($this->session, 'GET', '/'.$photoId.'/');
        //execute
        $response = $request->execute();
        //transform la data graphObject
        $pictureInfo = $response->getGraphObject();
        return $pictureInfo->asArray();
    }

    /**
     * @param string $permName
     * @return mixed
     * @throws \Facebook\FacebookRequestException
     */
    public function getUserPermissions($permName=""){
        if(isset($permName) && strlen($permName)>0){
            $request = new FacebookRequest($this->session,'GET','/me/permissions'.'/'.$permName);
        } else {
            $request = new FacebookRequest($this->session,'GET','/me/permissions');
        }
        //execute
        $response = $request->execute();
        //transform la data graphObject
        $perms = $response->getGraphObject();
        $permsArray = $perms->asArray();
        return $permsArray;
    }

    /**
     * @param $fbRequestedPerms
     * @return bool
     */
    public function checkPerms($fbRequestedPerms){
        foreach($fbRequestedPerms as $perm){
            $fbPerm = $this->getUserPermissions($perm);
            if(count($fbPerm)<1){
                return false;
            }
        }
        return true;
    }

}