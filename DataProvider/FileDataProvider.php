<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\DataProvider;

use RI\FileManagerBundle\Entity\File;

/**
 * Class FileDataProvider
 *
 * @package RI\FileManagerBundle\DataProvider
 */
class FileDataProvider extends DataProviderAbstract
{
    /**
     * Return list of files for root directory
     *
     * @return array
     */
    public function getRootDirectoryFiles()
    {
        return $this->getFilesFromDirectory(null);
    }

    /**
     * Returns list of files from directory
     *
     * @param integer|null $directoryId
     *
     * @return array
     */
    public function getFilesFromDirectory($directoryId)
    {
        $files = $this->entityManager->getRepository('RIFileManagerBundle:File')->findBy(array('directory' => $directoryId));

        foreach ($files as $key => $file) {
            $files[$key] = $this->convertFileEntityToArray($file);
        }

        return $files;
    }

    /**
     * Convert file entity to array format
     *
     * @param File $file
     *
     * @return array
     */
    public function convertFileEntityToArray(File $file)
    {
        $params = $file->getParams();
        $directory = $file->getDirectory();
        $fileData = array(
            'id' => $file->getId(),
            'dirId' => $directory ? $directory->getId() : 0,
            'name' => $file->getName(),
            'src' => $file->getPath(),
            'mime' => $params->getMime(),
            'width' => $params->getWidth(),
            'height' => $params->getHeight()
        );

        return $fileData;
    }
}