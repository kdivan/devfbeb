<?php

require_once 'Framework/Modele.php';

/**
 * Fournit les services d'accès aux genres musicaux
 *
 * @author Baptiste Pesquet
 */
class Utilisateur extends Modele {

    /**
     * Insère un utilisateur dans la table utilisateur
     * @param $userObject
     * @return string
     * @throws Exception
     */
    public function insertUtilisateur($userObject){
        $insertUserArray = $this->prepareUserArray($userObject);
        $lastInsertId = $this->insert(DB_PREFIX.'utilisateurs',$insertUserArray);
        if($lastInsertId > 0 ){
            return $lastInsertId;
        }else{
            //throw new Exception("L'utilisateur n'a pas été correctement insérer");
        }
    }

    /**
     * Recupere l'utilisateur en fonction du paramètre envoyé
     * @param $selectArray
     * @return mixed
     * @throws Exception
     */
    public function getUtilisateur($selectArray){
        //var_dump($selectArray);
        //$keyVal = each($selectArray);
        //var_dump($keyVal);
        $sql = "SELECT * FROM " .DB_PREFIX. "utilisateurs WHERE ".$selectArray[0]."=?";
        $user = $this->executerRequete($sql,array($selectArray[1]));
        if ($user->rowCount() > 0){
            return $user->fetch();  // Accès à la première ligne de résultat
        }else{
            return false;
        }
    }

    public function updateUtilisateur(){

    }

    public function deleteUtilisateur(){

    }

    /**
     * Prepare un tableau afin de pouvoir utiliser la fonction insert()
     * @param $userObject
     * @return mixed
     */
    private function prepareUserArray($userObject){
        $userArray                          = $userObject->asArray();
        $insertUserArray['facebook_id']     = $userArray['id'];
        $insertUserArray['facebook_link']   = $userArray['link'];
        $insertUserArray['nom']             = $userArray['last_name'];
        $insertUserArray['prenom']          = $userArray['first_name'];
        $insertUserArray['genre']           = ($userArray['gender']=='male' ? 'M' : 'F');
        $insertUserArray['localisation']    = $userArray['locale'];
        $insertUserArray['email']           = $userArray['email'];
        return $insertUserArray;
    }

}