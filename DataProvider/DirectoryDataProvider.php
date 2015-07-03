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

use RI\FileManagerBundle\Entity\Directory;

/**
 * Class DirectoryDataProvider
 *
 * @package RI\FileManagerBundle\DataProvider
 */
class DirectoryDataProvider extends DataProviderAbstract
{
    /**
     * @return mixed
     */
    public function getRootSubDirectories()
    {
        return $this->getDirectorySubDirectories(null);
    }

    /**
     * @param integer|null $directoryId
     *
     * @return array
     */
    public function getDirectorySubDirectories($directoryId)
    {
        $directories = $this->entityManager->getRepository('RIFileManagerBundle:Directory')->findByParent($directoryId);

        foreach ($directories as $key => $directory) {
            $directories[$key] = $this->convertDirectoryEntityToArray($directory);
        }

        return $directories;
    }


    /**
     * @param Directory $directory
     *
     * @return array
     */
    public function convertDirectoryEntityToArray(Directory $directory)
    {
        return array(
            'id' => $directory->getId(),
            'parent_id' => ($directory->getParent()) ? $directory->getParent()->getId() : 0,
            'name' => $directory->getName(),
            'createAt' => $directory->getCreateAt()
        );
    }

    /**
     * @param Directory $directory
     * @param bool      $convert
     *
     * @return array
     */
    public function getDirectoryParentsList(Directory $directory, $convert = true)
    {
        $parentsArray = array();
        while ($directory->getParent()) {
            $parent = $directory->getParent();
            if ($convert) {
                $parent = $this->convertDirectoryEntityToArray($parent);
            }
            array_unshift($parentsArray, $parent);
            $directory = $directory->getParent();
        }

        return $parentsArray;
    }
} 