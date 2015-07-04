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
use RI\FileManagerBundle\Model\UploadedFileParametersModel;

/**
 * File
 *
 * @ORM\Table(name="rifilemanager_files")
 * @ORM\Entity(repositoryClass="RI\FileManagerBundle\Entity\FileRepository")
 * @ORM\HasLifecycleCallbacks
 */
class File
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
     * @ORM\ManyToOne(targetEntity="Directory")
     * @ORM\JoinColumn(name="dir_id", referencedColumnName="id", nullable=true)
     */
    private $directory;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="path", type="string", length=255)
     */
    private $path;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_at", type="datetime")
     */
    private $createAt;

    /**
     * @var UploadedFileParametersModel
     *
     * @ORM\Column(name="params", type="object")
     */
    private $params;

    /**
     * @ORM\PrePersist
     */
    public function setAutoCreateAt()
    {
        $this->createAt = new \DateTime();
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
     * @return File
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
     * @return File
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
     * Set params
     *
     * @param UploadedFileParametersModel $params
     *
     * @return File
     */
    public function setParams(UploadedFileParametersModel $params)
    {
        $this->params = $params;

        return $this;
    }

    /**
     * Get params
     *
     * @return UploadedFileParametersModel
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * Set directory
     *
     * @param Directory $directory
     *
     * @return File
     */
    public function setDirectory(Directory $directory = null)
    {
        $this->directory = $directory;

        return $this;
    }

    /**
     * Get directory
     *
     * @return Directory
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return File
     */
    public function setPath($path)
    {
        $this->path = $path;
    
        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }
}