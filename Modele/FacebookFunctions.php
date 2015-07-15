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
     * @param $fbPhotoId
     * @param string $url
     * @return array
     * @throws \Facebook\FacebookRequestException
     */
    public function getPictureInfo($fbPhotoId,$url=""){
        $token = (String)$this->session->getAccessToken();
        $_SESSION['fb_token'] = $token;
        //prepare
        $request = new FacebookRequest($this->session, 'GET', '/'.$fbPhotoId.'/');
        //execute
        $response = $request->execute();
        //transform la data graphObject
        $pictureInfo = $response->getGraphObject();
        $pictureInfoArray = $pictureInfo->asArray();
        /*if( strlen($url)> 0) {
            $pictureFbStats = $this->getFbStats($url);
            if ($pictureFbStats) {
                // utile pour le tri par nb like
                $statsArray['total_count']      = $pictureFbStats[0]->total_count;
                $statsArray['like_count']       = $pictureFbStats[0]->like_count;
                $statsArray['comment_count']    = $pictureFbStats[0]->comment_count;
                $statsArray['share_count']      = $pictureFbStats[0]->share_count;
                $statsArray['click_count']      = $pictureFbStats[0]->click_count;
                return array_merge($pictureInfoArray, $statsArray);
            }
        }*/
        return $pictureInfoArray;
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

    /**
     * @param $url
     * @return mixed
     */
    public function getFbStats($url){
        $query = "select total_count,like_count,comment_count,share_count,click_count from link_stat where url='{$url}'";
        $call = "https://api.facebook.com/method/fql.query?query=" . rawurlencode($query) . "&format=json";
        $output = file_get_contents($call);
        return json_decode($output);
    }

}