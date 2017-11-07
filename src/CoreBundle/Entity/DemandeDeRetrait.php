<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * DemandeDeRetrait
 *
 * @ORM\Table(name="demande_de_retrait")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\DemandeDeRetraitRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class DemandeDeRetrait
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(name="montant", type="integer")
     * @Assert\NotBlank()
     * @Assert\Type(type="int")
     * @Assert\Range(min=1)
     */
    private $montant;

    /**
     * @var bool
     *
     * @ORM\Column(name="retirer", type="boolean")
     */
    private $retirer;

    /**
     * @var bool
     *
     * @ORM\Column(name="annuler", type="boolean")
     */
    private $annuler;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_demande", type="datetime")
     */
    private $dateDemande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_retrait", type="datetime", nullable=true)
     */
    private $dateRetrait;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_annulation", type="datetime", nullable=true)
     */
    private $dateAnnulation;

    /**
     * @var int
     *
     * @ORM\Column(name="fret_sms", type="integer")
     */
    private $fretSMS;

    /**
     * @var int
     *
     * @ORM\Column(name="commission", type="integer")
     */
    private $commission;

    /**
    *
    * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\User")
    * @ORM\JoinColumn(nullable=false)
    */
    private $user;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set montant
     *
     * @param integer $montant
     *
     * @return DemandeDeRetrait
     */
    public function setMontant($montant)
    {
        $this->montant = $montant;

        return $this;
    }

    /**
     * Get montant
     *
     * @return int
     */
    public function getMontant()
    {
        return $this->montant;
    }

    /**
     * Set retirer
     *
     * @param boolean $retirer
     *
     * @return DemandeDeRetrait
     */
    public function setRetirer($retirer)
    {
        $this->retirer = $retirer;

        return $this;
    }

    /**
     * Get retirer
     *
     * @return bool
     */
    public function getRetirer()
    {
        return $this->retirer;
    }

    /**
     * Set annuler
     *
     * @param boolean $annuler
     *
     * @return DemandeDeRetrait
     */
    public function setAnnuler($annuler)
    {
        $this->annuler = $annuler;

        return $this;
    }

    /**
     * Get annuler
     *
     * @return bool
     */
    public function getAnnuler()
    {
        return $this->annuler;
    }

    /**
     * Set dateDemande
     *
     * @param \DateTime $dateDemande
     *
     * @return DemandeDeRetrait
     */
    public function setDateDemande($dateDemande)
    {
        $this->dateDemande = $dateDemande;

        return $this;
    }

    /**
     * Get dateDemande
     *
     * @return \DateTime
     */
    public function getDateDemande()
    {
        return $this->dateDemande;
    }

    /**
     * Set dateRetrait
     *
     * @param \DateTime $dateRetrait
     *
     * @return DemandeDeRetrait
     */
    public function setDateRetrait($dateRetrait)
    {
        $this->dateRetrait = $dateRetrait;

        return $this;
    }

    /**
     * Get dateRetrait
     *
     * @return \DateTime
     */
    public function getDateRetrait()
    {
        return $this->dateRetrait;
    }

    /**
     * Set dateAnnulation
     *
     * @param \DateTime $dateAnnulation
     *
     * @return DemandeDeRetrait
     */
    public function setDateAnnulation($dateAnnulation)
    {
        $this->dateAnnulation = $dateAnnulation;

        return $this;
    }

    /**
     * Get dateAnnulation
     *
     * @return \DateTime
     */
    public function getDateAnnulation()
    {
        return $this->dateAnnulation;
    }

    /**
     * Set fretSMS
     *
     * @param integer $fretSMS
     *
     * @return DemandeDeRetrait
     */
    public function setFretSMS($fretSMS)
    {
        $this->fretSMS = $fretSMS;

        return $this;
    }

    /**
     * Get fretSMS
     *
     * @return int
     */
    public function getFretSMS()
    {
        return $this->fretSMS;
    }

    /**
     * Set commission
     *
     * @param integer $commission
     *
     * @return DemandeDeRetrait
     */
    public function setCommission($commission)
    {
        $this->commission = $commission;

        return $this;
    }

    /**
     * Get commission
     *
     * @return int
     */
    public function getCommission()
    {
        return $this->commission;
    }

    /**
     * Set user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return DemandeDeRetrait
     */
    public function setUser(\CoreBundle\Entity\User $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Get user
     *
     * @return \CoreBundle\Entity\User
     */
    public function getUser()
    {
        return $this->user;
    }
    
    /**
    * @ORM\PrePersist
    */
    public function prePersist() {
        $this->setDateDemande(new \Datetime());
    }
}
