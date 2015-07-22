<?php

require_once 'Framework/Modele.php';
require_once 'Modele/Participation.php';


/**
 * Class Concours
 */
class Concours extends Modele {

    private $idConcours;
    private $dateDebut;
    private $dateFin;
    private $nom;

    /**
     * @throws Exception
     */
    public function __construct(){
        $this->initVar($this->getCurrentConcoursInfo());
    }

    /**
     * @return mixed
     * @throws Exception
     */
    public function getCurrentConcoursInfo() {
        $sql = "SELECT * from ".DB_PREFIX. "concours
                    WHERE actif=? ";
        $concoursInfo = $this->executerRequete($sql, array('1'));
        if ($concoursInfo->rowCount() > 0){
            return $concoursInfo->fetch();  // Accès à la première ligne de résultat
        }
    }

    public function isCurrentConcoursFinished() {
        $sql = "SELECT * from ".DB_PREFIX. "concours
                    WHERE actif=?
                    AND date_fin > NOW()";
        $concoursInfo = $this->executerRequete($sql, array('1'));
        if ($concoursInfo->rowCount() > 0){
            return false;  // Accès à la première ligne de résultat
        }
        //concours finit
        return true;
    }

    /**
     *
     */
    public function getConcoursPrize(){
        $sql = "SELECT * from ".DB_PREFIX. "concours_prize
                    WHERE fk_concours_id = ? AND actif=? ORDER BY prize_position ASC";
        $concoursPrize = $this->executerRequete( $sql, array($this->idConcours,'1') );
        return $concoursPrize->fetchAll();
    }

    /**
     * @param $concoursInfo
     */
    public function initVar($concoursInfo){
        $this->setIdConcours($concoursInfo['id']);
        $this->setDateDebut($concoursInfo['date_debut']);
        $this->setDateFin($concoursInfo['date_fin']);
        $this->setNom($concoursInfo['nom']);
    }


    /**
     * @return mixed
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param mixed $nom
     */
    private function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return mixed
     */
    public function getDateFin()
    {
        return $this->dateFin;
    }

    /**
     * @param mixed $dateFin
     */
    private function setDateFin($dateFin)
    {
        $this->dateFin = $dateFin;
    }

    /**
     * @return mixed
     */
    public function getDateDebut()
    {
        return $this->dateDebut;
    }

    /**
     * @param mixed $dateDebut
     */
    private function setDateDebut($dateDebut)
    {
        $this->dateDebut = $dateDebut;
    }

    /**
     * @return mixed
     */
    public function getIdConcours()
    {
        return $this->idConcours;
    }

    /**
     * @param mixed $idConcours
     */
    private function setIdConcours($idConcours)
    {
        $this->idConcours = $idConcours;
    }

}