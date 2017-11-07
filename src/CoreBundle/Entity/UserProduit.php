<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * UserProduit
 *
 * @ORM\Table(name="user_produit")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\UserProduitRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class UserProduit
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
     * @var \DateTime
     *
     * @ORM\Column(name="date_commande", type="datetime")
     */
    private $dateCommande;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_cloture", type="datetime", nullable=true)
     */
    private $dateCloture;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_annulation", type="datetime", nullable=true)
     */
    private $dateAnnulation;

    /**
     * @var bool
     *
     * @ORM\Column(name="cloturer", type="boolean")
     */
    private $cloturer;

    /**
     * @var boll
     *
     * @ORM\Column(name="annuler", type="boolean")
     */
    private $annuler;

    /**
     * @var int
     *
     * @ORM\Column(name="qte", type="integer")
     */
    private $qte;
    
    /**
     * @var int
     *
     * @ORM\Column(name="prix_produit", type="integer")
     */
    private $prixProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="commission_produit", type="integer")
     */
    private $commissionProduit;

    /**
     * @var int
     *
     * @ORM\Column(name="point_produit", type="integer")
     */
    private $pointProduit;
    
    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\User")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;
    
    /**
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\Produit")
     * @ORM\JoinColumn(nullable=false)
     */
    private $produit;
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setDateCommande(new \Datetime());
        $this->setAnnuler(false);
        $this->setCloturer(false);
        
        $p = $this->getProduit();
        
        $this->setPrixProduit($p->getPrix());
        $this->setCommissionProduit($p->getCommission());
        $this->setPointProduit($p->getPoint());
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set dateCommande
     *
     * @param \DateTime $dateCommande
     *
     * @return UserProduit
     */
    public function setDateCommande($dateCommande)
    {
        $this->dateCommande = $dateCommande;

        return $this;
    }

    /**
     * Get dateCommande
     *
     * @return \DateTime
     */
    public function getDateCommande()
    {
        return $this->dateCommande;
    }

    /**
     * Set dateCloture
     *
     * @param \DateTime $dateCloture
     *
     * @return UserProduit
     */
    public function setDateCloture($dateCloture)
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    /**
     * Get dateCloture
     *
     * @return \DateTime
     */
    public function getDateCloture()
    {
        return $this->dateCloture;
    }

    /**
     * Set dateAnnulation
     *
     * @param \DateTime $dateAnnulation
     *
     * @return UserProduit
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
     * Set cloturer
     *
     * @param boolean $cloturer
     *
     * @return UserProduit
     */
    public function setCloturer($cloturer)
    {
        $this->cloturer = $cloturer;

        return $this;
    }

    /**
     * Get cloturer
     *
     * @return boolean
     */
    public function getCloturer()
    {
        return $this->cloturer;
    }

    /**
     * Set annuler
     *
     * @param boolean $annuler
     *
     * @return UserProduit
     */
    public function setAnnuler($annuler)
    {
        $this->annuler = $annuler;

        return $this;
    }

    /**
     * Get annuler
     *
     * @return boolean
     */
    public function getAnnuler()
    {
        return $this->annuler;
    }

    /**
     * Set qte
     *
     * @param integer $qte
     *
     * @return UserProduit
     */
    public function setQte($qte)
    {
        $this->qte = $qte;

        return $this;
    }

    /**
     * Get qte
     *
     * @return integer
     */
    public function getQte()
    {
        return $this->qte;
    }

    /**
     * Set prixProduit
     *
     * @param integer $prixProduit
     *
     * @return UserProduit
     */
    public function setPrixProduit($prixProduit)
    {
        $this->prixProduit = $prixProduit;

        return $this;
    }

    /**
     * Get prixProduit
     *
     * @return integer
     */
    public function getPrixProduit()
    {
        return $this->prixProduit;
    }

    /**
     * Set commissionProduit
     *
     * @param integer $commissionProduit
     *
     * @return UserProduit
     */
    public function setCommissionProduit($commissionProduit)
    {
        $this->commissionProduit = $commissionProduit;

        return $this;
    }

    /**
     * Get commissionProduit
     *
     * @return integer
     */
    public function getCommissionProduit()
    {
        return $this->commissionProduit;
    }

    /**
     * Set pointProduit
     *
     * @param integer $pointProduit
     *
     * @return UserProduit
     */
    public function setPointProduit($pointProduit)
    {
        $this->pointProduit = $pointProduit;

        return $this;
    }

    /**
     * Get pointProduit
     *
     * @return integer
     */
    public function getPointProduit()
    {
        return $this->pointProduit;
    }

    /**
     * Set user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return UserProduit
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
     * Set produit
     *
     * @param \CoreBundle\Entity\Produit $produit
     *
     * @return UserProduit
     */
    public function setProduit(\CoreBundle\Entity\Produit $produit)
    {
        $this->produit = $produit;

        return $this;
    }

    /**
     * Get produit
     *
     * @return \CoreBundle\Entity\Produit
     */
    public function getProduit()
    {
        return $this->produit;
    }
}
