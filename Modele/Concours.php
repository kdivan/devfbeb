<?php

require_once 'Framework/Modele.php';

/**
 * Class Concours
 */
class Concours extends Modele {

    private $idConcours;
    private $dateDebut;
    private $dateFin;
    private $nom;
    private $dateResultat;

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
                    WHERE actif=?
                    AND date_fin > NOW()";
        $concoursInfo = $this->executerRequete($sql, array('1'));
        if ($concoursInfo->rowCount() > 0){
            return $concoursInfo->fetch();  // Accès à la première ligne de résultat
        }else{
            throw new Exception ("Le concours est terminé");;
        }
    }

    /**
     * @param $concoursInfo
     */
    public function initVar($concoursInfo){
        $this->setIdConcours($concoursInfo['id']);
        $this->setDateDebut($concoursInfo['date_debut']);
        $this->setDateFin($concoursInfo['date_fin']);
        $this->setNom($concoursInfo['nom']);
        $this->setDateResultat($concoursInfo['date_resultat']);
    }


    /**
     * @return mixed
     */
    public function getDateResultat()
    {
        return $this->dateResultat;
    }

    /**
     * @param mixed $dateResultat
     */
    private function setDateResultat($dateResultat)
    {
        $this->dateResultat = $dateResultat;
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