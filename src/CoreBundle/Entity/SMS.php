<?php
namespace CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="sms")
 * @ORM\Entity(repositoryClass="CoreBundle\Repository\SMSRepository")
 * @ORM\HasLifecycleCallbacks()
 */
class SMS {
    /**
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(name="content", type="string", length=255)
     */
    private $content;

    /**
     * @var boolean
     * @ORM\Column(name="payer", type="boolean")
     */
    private $payer;

    /**
     * @var \DateTime
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;
    
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="CoreBundle\Entity\User")
     * @ORM\JoinColumn(nullable=true)
     */
    private $user;

    /**
     * @ORM\PrePersist
     */
    public function prePersist() {
        $this->setCreateAt(new \Datetime())
            ->setPayer(false);
    }

    /**
     * @return int
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param string $content
     * @return SMS
     */
    public function setContent($content) {
        $this->content = $content;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent() {
        return $this->content;
    }

    /**
     * @param \DateTime $createAt
     * @return SMS
     */
    public function setCreateAt($createAt) {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreateAt() {
        return $this->createAt;
    }

    /**
     * Set payer
     *
     * @param boolean $payer
     *
     * @return SMS
     */
    public function setPayer($payer)
    {
        $this->payer = $payer;

        return $this;
    }

    /**
     * Get payer
     *
     * @return boolean
     */
    public function getPayer()
    {
        return $this->payer;
    }

    /**
     * Set user
     *
     * @param \CoreBundle\Entity\User $user
     *
     * @return SMS
     */
    public function setUser(\CoreBundle\Entity\User $user = null)
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
}
