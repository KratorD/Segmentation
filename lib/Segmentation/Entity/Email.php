<?php

/**
 * Mail
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 */
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use DoctrineExtensions\StandardFields\Mapping\Annotation as ZK;

/**
 * Mail entity class
 *
 * Annotations define the entity mappings to database.
 *
 * @ORM\Entity(repositoryClass="Segmentation_Entity_Repository_EmailRepository")
 * @ORM\Table(name="Segmentation_Mail")
 */
class Segmentation_Entity_Email extends Zikula_EntityAccess
{

    /**
     * id field (record id)
     *
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * email id
     * @ORM\Column(length=50)
     */
    private $codEmail;

    /**
     * description
     * 
     * @ORM\Column(length=255)
     */
    private $description;

	/**
     * email body
     * @ORM\Column(type="text")
     */
    private $body;

	/**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="create")
     */
    private $createdUserId;

    /**
     * @ORM\Column(type="integer")
     * @ZK\StandardFields(type="userid", on="update")
     */
    private $updatedUserId;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="create")
     */
    private $createdTime;

    /**
     * @ORM\Column(type="datetime")
     * @Gedmo\Timestampable(on="update")
     */
    private $updatedTime;

    /**
     * Constructor 
     */
    public function __construct()
    {

    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getCodEmail()
    {
        return $this->codEmail;
    }

    public function setCodEmail($codEmail)
    {
        $this->codEmail = $codEmail;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

	public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

	/**
     * Get created user id.
     *
     * @return integer
     */
    public function getCreatedUserId()
    {
        return $this->createdUserId;
    }
    
    /**
     * Set created user id.
     *
     * @param integer $createdUserId.
     *
     * @return void
     */
    public function setCreatedUserId($createdUserId)
    {
        $this->createdUserId = $createdUserId;
    }
    
    /**
     * Get updated user id.
     *
     * @return integer
     */
    public function getUpdatedUserId()
    {
        return $this->updatedUserId;
    }
    
    /**
     * Set updated user id.
     *
     * @param integer $updatedUserId.
     *
     * @return void
     */
    public function setUpdatedUserId($updatedUserId)
    {
        $this->updatedUserId = $updatedUserId;
    }
    
    /**
     * Get created date.
     *
     * @return datetime
     */
    public function getCreatedTime()
    {
        return $this->createdTime;
    }
    
    /**
     * Set created date.
     *
     * @param datetime $createdDate.
     *
     * @return void
     */
    public function setCreatedTime($createdTime)
    {
        $this->createdTime = $createdTime;
    }
    
    /**
     * Get updated date.
     *
     * @return datetime
     */
    public function getUpdatedTime()
    {
        return $this->updatedTime;
    }
    
    /**
     * Set updated date.
     *
     * @param datetime $updatedDate.
     *
     * @return void
     */
    public function setUpdatedTime($updatedTime)
    {
        $this->updatedTime = $updatedTime;
    }
}
