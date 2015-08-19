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

use Doctrine\ORM\EntityRepository;

/**
 * Class FileRepository
 *
 * @package RI\FileManagerBundle\Entity
 */
class FileRepository extends EntityRepository
{
    /**
     * @param Directory $directory
     *
     * @return array
     */
    public function fetchFromDir(Directory $directory)
    {
        return $this->findBy(array('directory' => $directory));
    }


    /**
     * @param string $checksum
     * @param string $path
     *
     * @return null|object
     */
    public function findFileByChecksum($checksum, $path = null)
    {
        $find = array('checksum' => $checksum);
        if ($path) {
            $find['path'] = $path;
        }

        return $this->findOneBy($find);
    }


    /**
     * @return array
     */
    public function fetchFilesWithoutChecksum()
    {
        return $this->findBy(array('checksum' => ''));
    }
} 