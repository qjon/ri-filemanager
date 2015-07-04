<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Model\Selection;

use Doctrine\ORM\EntityManager;
use RI\FileManagerBundle\Entity\DirectoryRepository;
use RI\FileManagerBundle\Entity\FileRepository;
use RI\FileManagerBundle\Exceptions\RemoveDirException;

/**
 * Class DeleteSelectionModel
 *
 * @package RI\FileManagerBundle\Model\Selection
 */
class DeleteSelectionModel extends AbstractSelectionModel
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * List of paths to remove
     *
     * @var array
     */
    private $filesToRemove = array();

    /**
     * @param FileRepository      $fileRepository
     * @param DirectoryRepository $directoryRepository
     * @param EntityManager       $entityManager
     */
    public function __construct(FileRepository $fileRepository, DirectoryRepository $directoryRepository, EntityManager $entityManager)
    {
        parent::__construct($fileRepository, $directoryRepository);
        $this->entityManager = $entityManager;
    }

    /**
     * @param array $filesIds    - list of files ids to remove
     * @param array $foldersIds  - list of folders ids to remove
     *
     * @return bool
     * @throws RemoveDirException
     */
    public function delete(array $filesIds, array $foldersIds = array())
    {
        if (empty($filesIds) && empty($foldersIds)) {
            return false;
        }

        foreach ($filesIds as $fileId) {
            $file = $this->getFileById($fileId);
            $this->filesToRemove[] = $file->getPath();
            $this->entityManager->remove($file);
        }

        foreach ($foldersIds as $dirId) {
            $dir = $this->getDirectoryById($dirId);

            $files = $this->fileRepository->fetchFromDir($dir);

            if (!empty($files)) {
                throw new RemoveDirException('Directory is not empty (files): ' . $dir->getName());
            }

            $subdirs = $dir->getChildren();
            if ($subdirs->count() > 0) {
                throw new RemoveDirException('Directory is not empty (dirs): ' . $dir->getName());
            }

            $this->entityManager->remove($dir);
        }

        return true;
    }

    /**
     * @return array
     */
    public function getRemovedFiles()
    {
        return $this->filesToRemove;
    }
}