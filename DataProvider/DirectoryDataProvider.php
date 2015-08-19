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
        $directories = $this->entityManager->getRepository('RIFileManagerBundle:Directory')->findBy(array('parent' => $directoryId));

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
        $parent = $directory->getParent();

        return array(
            'id' => $directory->getId(),
            'parent_id' => ($parent) ? $parent->getId() : 0,
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
        $parent = $directory->getParent();
        while ($parent) {
            if ($convert) {
                $parentData = $this->convertDirectoryEntityToArray($parent);
                array_unshift($parentsArray, $parentData);
            }
            else
            {
                array_unshift($parentsArray, $parent);
            }
            $parent = $parent->getParent();
        }

        return $parentsArray;
    }
} 