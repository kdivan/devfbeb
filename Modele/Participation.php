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
        if($lastInsertId > 0 ){
            return $lastInsertId;
        }else{
            throw new Exception("La participation n'a pas été correctement insérer");
        }
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
        $sql = "SELECT ". DB_PREFIX ."participation.*,id as id_participation FROM " .DB_PREFIX. "participation WHERE ".$keyVal['key']."=?";
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
            $sql = "SELECT ". DB_PREFIX ."participation.*,id as id_participation
                    FROM ". DB_PREFIX ."participation
                        WHERE actif = ?
                        AND fk_concours_id = ?";
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
     * @throws Exception
     */
    public function hasUserParticipateCurrentConcours($userId){
        $sql = "SELECT * FROM ".DB_PREFIX."participation
                    WHERE fk_utilisateur_id = ?
                    AND fk_concours_id = ?
                    AND actif = ? ";
        $checkData    = [$userId,$this->concours->getIdConcours(),"1"];
        $checkParticipation = $this->executerRequete($sql,$checkData);
        if( $checkParticipation->rowCount()>0 ){
            //throw new Exception("Vous avez déjà participer au jeu");
        }
    }

    /**
     * Retourne la liste des participations en fonction de la limite min et la limite max
     * @param $limitMin
     * @param $limitMax
     * @return array
     */
    public function getParticipationWithLimit($limitMin, $limitMax){
        $sql = "SELECT ". DB_PREFIX ."participation.*,id as id_participation
                    FROM ". DB_PREFIX ."participation
                        WHERE actif = ?
                        AND fk_concours_id = ?
                        ORDER BY date_participation
                        LIMIT ".$limitMin.",".$limitMax;
        $participationList = $this->executerRequete( $sql,array("1",$this->concours->getIdConcours()) );
        $participationListArray = $participationList->fetchAll();
        $participationList->closeCursor();
        return $participationListArray;
    }

}