<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Directory
 *
 * @ORM\Table(name="rifilemanager_directories")
 * @ORM\Entity(repositoryClass="RI\FileManagerBundle\Entity\DirectoryRepository")
 * @ORM\HasLifecycleCallbacks
 * @codeCoverageIgnore
 */
class Directory
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @ORM\ManyToOne(targetEntity="Directory", inversedBy="children")
     * @ORM\JoinColumn(name="parent_id", referencedColumnName="id")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity="Directory", mappedBy="parent")
     */
    private $children;

    /**
     * @ORM\PrePersist
     */
    public function setAutoCreateAt()
    {
        $this->createAt = new \DateTime();
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->children = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function __clone()
    {
        $this->id = null;
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
     * Set name
     *
     * @param string $name
     *
     * @return Directory
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set create_at
     *
     * @param \DateTime $createAt
     *
     * @return Directory
     */
    public function setCreateAt($createAt)
    {
        $this->createAt = $createAt;

        return $this;
    }

    /**
     * Get create_at
     *
     * @return \DateTime 
     */
    public function getCreateAt()
    {
        return $this->createAt;
    }

    /**
     * Set parent
     *
     * @param \RI\FileManagerBundle\Entity\Directory $parent
     *
     * @return Directory
     */
    public function setParent(\RI\FileManagerBundle\Entity\Directory $parent = null)
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * Get parent
     *
     * @return \RI\FileManagerBundle\Entity\Directory 
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Add children
     *
     * @param \RI\FileManagerBundle\Entity\Directory $children
     * @return Directory
     */
    public function addChild(\RI\FileManagerBundle\Entity\Directory $children)
    {
        $this->children[] = $children;

        return $this;
    }

    /**
     * Remove children
     *
     * @param \RI\FileManagerBundle\Entity\Directory $children
     */
    public function removeChild(\RI\FileManagerBundle\Entity\Directory $children)
    {
        $this->children->removeElement($children);
    }
}
