<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Model;

use Doctrine\ORM\EntityManager;
use RI\FileManagerBundle\Entity\File;

/**
 * Allow crop images, save cropped image and change file width and height properties
 *
 * @package RI\FileManagerBundle\Model
 */
class CropImageModel
{
    /**
     * @var File
     */
    private $file;

    /**
     * @var \Imagick
     */
    private $imagick;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var string
     */
    private $pathToUploadDir;


    /**
     * @param EntityManager $entityManager
     * @param string        $rootDir
     */
    public function __construct(EntityManager $entityManager, $rootDir)
    {
        $this->entityManager = $entityManager;
        $this->pathToUploadDir = $rootDir . '/../web';
    }

    /**
     * @param File $file
     */
    public function setFile(File $file)
    {
        $this->file = $file;
        $this->imagick = new \Imagick($this->pathToUploadDir . $file->getPath());
    }

    /**
     * @param integer $x
     * @param integer $y
     * @param integer $width
     * @param integer $height
     *
     * @return boolean
     */
    public function crop($x, $y, $width, $height)
    {
        try {
            $this->imagick->cropimage($width, $height, $x, $y);
            $this->imagick->writeimage($this->pathToUploadDir . $this->file->getPath());
            $this->file->getParams()->setWidth($width);
            $this->file->getParams()->setHeight($height);

            $this->entityManager->flush();

            return true;
        } catch (\Exception $exception) {
            return false;
        }
    }
}