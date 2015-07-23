<?php

require_once 'Framework/Modele.php';
require_once 'Modele/Concours.php';


/**
 * Class Participation
 */
class Participation extends Modele {

    /**
     * @var Concours
     */
    protected $concours;

    public function __construct(){
        $this->concours = new Concours();
    }

    /**
     * @param $participationArray
     * @return string
     * @throws Exception
     */
    public function insertParticipation($participationArray){
        $lastInsertId = $this->insert(DB_PREFIX.'participation',$participationArray);
        if( $lastInsertId  ){
            $sql = "SELECT max(id) as max_id FROM ".DB_PREFIX."participation where actif=1";
            $res = $this->executerRequete($sql)->fetch();
            if( $res ){
                return $res['max_id'];
            } else {
                throw new Exception("La participation n'a pas été correctement insérer".$lastInsertId);
            }
        }else{
            throw new Exception("La participation n'a pas été correctement insérer".$lastInsertId);
        }
        //return $lastInsertId;
    }

    /**
     * @param $fbParticipationId
     * @return PDOStatement
     */
    public function disableParticipation($fbParticipationId){
        $sql = "UPDATE ".DB_PREFIX.'participation SET actif = ? WHERE facebook_photo_id = ?';
        $updateParticipation = $this->executerRequete( $sql,array("0",$fbParticipationId) );
        return $updateParticipation;
    }

    /**
     * @param $fbParticipationId
     * @return PDOStatement
     */
    public function enableParticipation($fbParticipationId){
        $sql = "UPDATE ".DB_PREFIX.'participation SET actif = ? WHERE facebook_photo_id = ?';
        $updateParticipation = $this->executerRequete( $sql,array("1",$fbParticipationId) );
        return $updateParticipation;
    }

    /**
     * Recupere l'utilisateur en fonction du paramètre envoyé
     * @param $selectArray
     * @return mixed
     * @throws Exception
     */
    public function getParticipation($selectArray){
        $keyVal = each($selectArray);
        $sql = "SELECT * FROM " .DB_PREFIX. "participation WHERE ".$keyVal['key']."=?";
        $participation = $this->executerRequete($sql,array($keyVal['value']));
        if ($participation->rowCount() > 0){
            return $participation->fetch();  // Accès à la première ligne de résultat
        }else{
            return false;
        }
    }

    /**
     * @param $selectArray
     * @return bool|mixed
     */
    public function findBy($selectArray){
        $keyVal = each($selectArray);
        $sql = "SELECT ". DB_PREFIX ."participation.*,id as id_participation FROM " .DB_PREFIX. "participation WHERE ".$keyVal['key']."=? AND actif=1";
        $participation = $this->executerRequete($sql,array($keyVal['value']));
        if ($participation->rowCount() > 0){
            return $participation->fetch();  // Accès à la première ligne de résultat
        }else{
            return false;
        }
    }

    /**
     * @param null $param
     * @return array
     */
    public function getParticpationFromCurrentConcours($param=NULL){
        if( !(is_null($param)) ){

        }else{
            $sql = "SELECT p.*,u.*,p.id as id_participation
                    FROM ". DB_PREFIX ."participation as p,". DB_PREFIX ."utilisateurs as u
                        WHERE p.actif = ?
                        AND p.fk_concours_id = ?
                        AND p.fk_utilisateur_id=u.id";
            $participationList = $this->executerRequete( $sql,array("1",$this->concours->getIdConcours()) );
            $participationListArray = $participationList->fetchAll();
            $participationList->closeCursor();
            return $participationListArray;
        }
    }

    public function updateParticipation(){

    }

    public function deleteParticipation(){

    }

    /**
     * @param $userId
     * @return bool|mixed
     */
    public function hasUserParticipateCurrentConcours($userId){
        $sql = "SELECT * FROM ".DB_PREFIX."participation
                    WHERE fk_utilisateur_id = ?
                    AND fk_concours_id = ?
                    AND actif = ? ";
        $checkData    = [$userId,$this->concours->getIdConcours(),"1"];
        $checkParticipation = $this->executerRequete($sql,$checkData);
        if( $checkParticipation->rowCount()>0 ){
            return $checkParticipation->fetch();
        } else {
            return false;
        }
    }

    /**
     * Retourne la liste des participations en fonction de la limite min et la limite max
     * @param $limitMin
     * @param $limitMax
     * @return array
     */
    public function getParticipationWithLimit($limitMin, $limitMax,$selectedFilter="more_recent"){
        if( strcmp($selectedFilter,'more_recent')==0 ){
            $filter = "date_participation DESC";
        } elseif (strcmp($selectedFilter,'less_recent')==0 ){
            $filter = "date_participation ASC";
        }
        $sql = "SELECT ". DB_PREFIX ."participation.*,id as id_participation
                    FROM ". DB_PREFIX ."participation
                        WHERE actif = ?
                        AND fk_concours_id = ?
                        ORDER BY ".$filter."
                        LIMIT ".$limitMax." offset ".$limitMin;
        $participationList = $this->executerRequete( $sql,array("1",$this->concours->getIdConcours()) );
        $participationListArray = $participationList->fetchAll();
        $participationList->closeCursor();
        return $participationListArray;
    }

    /**
     * @return array
     */
    public function getAllParticipationCurrentConcours(){
        $allParticipation = $this->getParticpationFromCurrentConcours();
        foreach($allParticipation as $part){
            $fbPhotoInfo            = $this->fb->getPictureInfo($part['facebook_photo_id'],SERVER_NAME.'photo/participation/'.$part['id_participation']);
            $participationData[]    = array_merge($part,$fbPhotoInfo);
        }
        return $participationData;
    }

}