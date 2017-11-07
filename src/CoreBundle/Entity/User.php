<?php
namespace CoreBundle\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\UserRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="tel", message="Cet numero de telephone est deja existent")
 * @UniqueEntity(fields="email", message="Cet email est deja existente")
 */
class User implements UserInterface {
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
    
    /**
     * @var string
     * @ORM\Column(name="code", type="string", length=255, unique=true, nullable=true)
     */
    private $code;

    /**
     * @var string
     * @ORM\Column(name="code_parrain", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $codeParrain;

    /**
     * @var string
     * @ORM\Column(name="compte_upgrade", type="integer")
     */
    private $compteUpgrade;
    
    /**
     * @var string
     * @ORM\Column(name="nom", type="string", length=255, nullable=true)
     * @Assert\NotBlank() 
     */
    private $nom;

    /**
     * @var string
     * @ORM\Column(name="prenoms", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $prenoms;

    /**
     * @var DateTime
     * @ORM\Column(name="naissance", type="date", nullable=true)
     */
    private $naissance;

    /**
     * @var Sexe
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Sexe")
     * @ORM\JoinColumn(nullable=true)
     */
    private $sexe;

    /**
     * @var CptPerGain
     * @ORM\OneToOne(targetEntity="CoreBundle\Entity\CptPerGain")
     * @ORM\JoinColumn(nullable=true)
     */
    private $cptPerGain;

    /**
    * @var string
    * @ORM\Column(name="profession", type="string", length=255, nullable=true)
    */
    private $profession;

    /**
    * @var string
    * @ORM\Column(name="tel", type="string", length=255, unique=true, nullable=true)
    * @Assert\NotBlank()
    */
    private $tel;

    /**
    * @var string
    * @ORM\Column(name="email", type="string", length=255, nullable=true, unique=true)
    * @Assert\Email()
    * @Assert\NotBlank()
    */
    private $email;

    /**
    * @var string
    * @ORM\Column(name="pays", type="string", length=255, nullable=true)
    * @Assert\NotBlank()
    */
    private $pays;

    /**
     * @var string
     * @ORM\Column(name="ville", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $ville;

    /**
     * @var string
     * @ORM\Column(name="adresse", type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @var string
     * @ORM\Column(name="password", type="string", length=255, nullable=true)
     * @Assert\NotBlank()
     */
    private $password;

    /**
     * @ORM\Column(name="salt", type="string", length=255, nullable=true)
     */
    private $salt;
    
    /**
     * @ORM\Column(name="rep", type="boolean")
     */
    private $rep;
    
    /**
     * @ORM\Column(name="user_id", type="integer", nullable=true)
     */
    private $userId;

    /**
     * @ORM\Column(name="cloturer", type="boolean")
     */
    private $cloturer;
    
    /**
     * @ORM\Column(name="suspendu", type="boolean")
     */
    private $suspendu;

    /**
     * @ORM\Column(name="roles", type="array", nullable=true)
     */
    private $roles = array();
    
    public function __construct() {
        $this->setCompteUpgrade(0);
    }
    
    /**
     * @ORM\PostPersist
     */
    public function postPersist() {
        $strId = ''.$this->getId();
        
        switch(count($strId)) {
            case 1:
                $_code = 'TB000';
                break;
            case 2:
                $_code = 'TB00';
                break;
            case 3:
                $_code = 'TB0';
                break;
            default:
                $_code = 'TB';
        }
        
        $this->setCode($_code.$strId);
    }
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setRep(false);
        $this->setCloturer(false);
        $this->setSuspendu(false);
    }

    public function eraseCredentials() {}

    public function getPassword() {
        return $this->password;
    }
    
    /**
     * @return array
     */
    public function getRoles() {
        return $this->roles;
    }

    /**
     * @return string
     */
    public function getSalt() {
        return $this->salt;
    }

    public function getUsername() {}

    /**
     * @return integer
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $code
     * @return User
     */
    public function setCode($code) {
        $this->code = $code;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode() {
        return $this->code;
    }

    /**
     * @param string $codeParrain
     * @return User
     */
    public function setCodeParrain($codeParrain) {
        $this->codeParrain = $codeParrain;
        return $this;
    }

    /**
     * @return string
     */
    public function getCodeParrain() {
        return $this->codeParrain;
    }

    /**
     * @param integer $compteUpgrade
     * @return User
     */
    public function setCompteUpgrade($compteUpgrade) {
        $this->compteUpgrade = $compteUpgrade;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCompteUpgrade() {
        return $this->compteUpgrade;
    }

    /**
     * @param string $nom
     * @return User
     */
    public function setNom($nom) {
        $this->nom = $nom;
        return $this;
    }

    /**
     * @return string
     */
    public function getNom() {
        return $this->nom;
    }

    /**
     * @param string $prenoms
     * @return User
     */
    public function setPrenoms($prenoms) {
        $this->prenoms = $prenoms;
        return $this;
    }

    /**
     * @return string
     */
    public function getPrenoms() {
        return $this->prenoms;
    }

    /**
     * @param DateTime $naissance
     * @return User
     */
    public function setNaissance($naissance) {
        $this->naissance = $naissance;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getNaissance() {
        return $this->naissance;
    }

    /**
     * @param string $profession
     * @return User
     */
    public function setProfession($profession) {
        $this->profession = $profession;
        return $this;
    }

    /**
     * @return string
     */
    public function getProfession() {
        return $this->profession;
    }

    /**
     * @param string $tel
     * @return User
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
     * @param string $email
     * @return User
     */
    public function setEmail($email) {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * @param string $pays
     * @return User
     */
    public function setPays($pays) {
        $this->pays = $pays;
        return $this;
    }

    /**
     * @return string
     */
    public function getPays() {
        return $this->pays;
    }

    /**
     * @param string $ville
     * @return User
     */
    public function setVille($ville) {
        $this->ville = $ville;
        return $this;
    }

    /**
     * @return string
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * @param string $adresse
     * @return User
     */
    public function setAdresse($adresse) {
        $this->adresse = $adresse;
        return $this;
    }

    /**
     * @return string
     */
    public function getAdresse() {
        return $this->adresse;
    }

    /**
     * @param string $password
     * @return User
     */
    public function setPassword($password) {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $salt
     * @return User
     */
    public function setSalt($salt) {
        $this->salt = $salt;
        return $this;
    }

    /**
     * @param array $roles
     * @return User
     */
    public function setRoles($roles) {
        $this->roles = $roles;
        return $this;
    }

    /**
     * @param Sexe $sexe
     * @return User
     */
    public function setSexe(Sexe $sexe) {
        $this->sexe = $sexe;

        return $this;
    }

    /**
     * @return Sexe
     */
    public function getSexe() {
        return $this->sexe;
    }

    /**
     * @param boolean $rep
     * @return User
     */
    public function setRep($rep) {
        $this->rep = $rep;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getRep() {
        return $this->rep;
    }

    /**
     * @param integer $userId
     * @return User
     */
    public function setUserId($userId) {
        $this->userId = $userId;
        return $this;
    }

    /**
     * @return integer
     */
    public function getUserId() {
        return $this->userId;
    }

    /**
     * @param boolean $cloturer
     * @return User
     */
    public function setCloturer($cloturer) {
        $this->cloturer = $cloturer;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCloturer() {
        return $this->cloturer;
    }

    /**
     * @param boolean $suspendu
     * @return User
     */
    public function setSuspendu($suspendu) {
        $this->suspendu = $suspendu;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getSuspendu()  {
        return $this->suspendu;
    }

    /**
     * @param \CoreBundle\Entity\CptPerGain $cptPerGain
     * @return User
     */
    public function setCptPerGain(\CoreBundle\Entity\CptPerGain $cptPerGain) {
        $this->cptPerGain = $cptPerGain;

        return $this;
    }

    /**
     * @return \CoreBundle\Entity\CptPerGain
     */
    public function getCptPerGain() {
        return $this->cptPerGain;
    }
}