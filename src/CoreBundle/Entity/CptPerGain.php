<?php
namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="cpt_per_gain")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\CptPerGainRepository")
 */
class CptPerGain {
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="nom", type="string", length=255)
     */
    private $nom;

    /**
     * @var string
     * @ORM\Column(name="prenom", type="string", length=255)
     */
    private $prenom;

    /**
     * @var string
     * @ORM\Column(name="tel", type="string", length=255)
     */
    private $tel;


    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $non
     * @return CptPerGain
     */
    public function setNon($non) {
        $this->non = $non;
        return $this;
    }

    /**
     * @return string
     */
    public function getNon() {
        return $this->non;
    }

    /**
     * @param string $prenom
     * @return CptPerGain
     */
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenom() {
        return $this->prenom;
    }

    /**
     * @param string $tel
     * @return CptPerGain
     */
    public function setTel($tel) {
        $this->tel = $tel;
        return $this;
    }

    /**
     * @return string
     */
    public function getTel() {
        return $this->tel;
    }

    /**
     * Set nom
     *
     * @param string $nom
     *
     * @return CptPerGain
     */
    public function setNom($nom)
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Get nom
     *
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }
}
