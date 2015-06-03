<?php

require_once 'Framework/Modele.php';

/**
 * Class Vote
 */
class Vote extends Modele {

    /**
     * @param $voteArray
     * @return string
     * @throws Exception
     */
    public function insertVote($voteArray){
        $lastInsertId = $this->insert(DB_PREFIX.'vote',$voteArray);
        if($lastInsertId > 0 ){
            return $lastInsertId;
        }else{
            throw new Exception("Le vote  n'a pas été correctement insérer");
        }
    }

    /**
     * @param $participationId
     * @return array
     */
    public function getAllVote($participationId){
        $sql = "SELECT * FROM " .DB_PREFIX. "vote WHERE fk_participation_id=?";
        $vote = $this->executerRequete($sql,[$participationId]);
        $voteListArray = $vote->fetchAll();
        $vote->closeCursor();
        return $voteListArray;
    }

    /**
     * Recupere un/plusieurs vote
     * @param $selectArray
     * @return mixed
     * @throws Exception
     */
    public function findVoteBy($selectArray){
        $keyVal = each($selectArray);
        $sql = "SELECT * FROM " .DB_PREFIX. "vote WHERE ".$keyVal['key']."=?";
        $vote = $this->executerRequete($sql,array($keyVal['value']));
        if ($vote->rowCount() > 0){
            return $vote->fetch();  // Accès à la première ligne de résultat
        }else{
            return false;
        }
    }

}