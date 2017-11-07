<?php
namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Formation
 *
 * @ORM\Table(name="formation")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\FormationRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class Formation
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
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     * @Assert\NotBlank(message="Titre requis")
     */
    private $titre;
    
    /**
     * @var \Datatime
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @var string
     *
     * @ORM\Column(name="file", type="string", length=255)
     * @Assert\NotBlank(message="Fichier requis")
     * @Assert\File(mimeTypes={ "application/pdf" })
     */
    private $file;

    /**
     * @var FormationType
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\FormationType")
     * @ORM\JoinColumn(nullable=true)
     * @Assert\NotBlank(message="Type de fichier requis")
     */
    private $formationType;
    
    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setCreateAt(new \Datetime());
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
     * Set titre
     *
     * @param string $titre
     *
     * @return Formation
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set file
     *
     * @param string $file
     *
     * @return Formation
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }

    /**
     * Get file
     *
     * @return string
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Set formationType
     *
     * @param \CoreBundle\Entity\FormationType $formationType
     *
     * @return Formation
     */
    public function setFormationType(\CoreBundle\Entity\FormationType $formationType = null)
    {
        $this->formationType = $formationType;

        return $this;
    }

    /**
     * Get formationType
     *
     * @return \CoreBundle\Entity\FormationType
     */
    public function getFormationType()
    {
        return $this->formationType;
    }

    /**
     * Set createAt
     *
     * @param \DateTime $createAt
     *
     * @return Formation
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
