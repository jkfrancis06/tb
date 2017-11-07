<?php

namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * Paiement
 *
 * @ORM\Table(name="paiement")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\PaiementRepository")
 * @ORM\HasLifecycleCallbacks()
 * @UniqueEntity(fields="code")
 * @UniqueEntity(fields="url")
 */
class Paiement
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
     * @var bigint
     *
     * @ORM\Column(name="code", type="bigint", unique=true)
     * @Assert\NotBlank()
     * @Assert\Type("int")
     */
    private $code;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=255)
     * @Assert\NotBlank()
     */
    private $tel;

    /**
     * @var bool
     *
     * @ORM\Column(name="valider", type="boolean")
     */
    private $valider;

    /**
     * @var bool
     *
     * @ORM\Column(name="inscrit", type="boolean")
     */
    private $inscrit;

    /**
     * @var string
     *
     * @ORM\Column(name="url", type="string", length=255, unique=true, nullable=true)
     */
    private $url;

    /**
     * @var \Datetime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setCreateAt(new \Datetime())
            ->setValider(false)
            ->setInscrit(false);
    }

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
     * Set code
     *
     * @param string $code
     *
     * @return Paiement
     */
    public function setCode($code)
    {
        $this->code = $code;

        return $this;
    }

    /**
     * Get code
     *
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * Set tel
     *
     * @param string $tel
     *
     * @return Paiement
     */
    public function setTel($tel)
    {
        $this->tel = $tel;

        return $this;
    }

    /**
     * Get tel
     *
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * Set valider
     *
     * @param boolean $valider
     *
     * @return Paiement
     */
    public function setValider($valider)
    {
        $this->valider = $valider;

        return $this;
    }

    /**
     * Get valider
     *
     * @return bool
     */
    public function getValider()
    {
        return $this->valider;
    }

    /**
     * Set inscrit
     *
     * @param boolean $inscrit
     *
     * @return Paiement
     */
    public function setInscrit($inscrit)
    {
        $this->inscrit = $inscrit;

        return $this;
    }

    /**
     * Get inscrit
     *
     * @return bool
     */
    public function getInscrit()
    {
        return $this->inscrit;
    }

    /**
     * Set url
     *
     * @param string $url
     *
     * @return Paiement
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Paiement
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get createAt
     *
     * @return \DateTime
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }
}
