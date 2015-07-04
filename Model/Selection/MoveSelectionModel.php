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

use RI\FileManagerBundle\DataProvider\DirectoryDataProvider;
use RI\FileManagerBundle\Entity\DirectoryRepository;
use RI\FileManagerBundle\Entity\FileRepository;
use RI\FileManagerBundle\Exceptions\CopyMoveSelectionException;

/**
 * Class MoveSelectionModel allow to move files and directories
 *
 * @package RI\FileManagerBundle\Model\Selection
 */
class MoveSelectionModel extends AbstractSelectionModel
{
    /**
     * @var DirectoryDataProvider
     */
    private $directoryProvider;

    /**
     * @param FileRepository        $fileRepository
     * @param DirectoryRepository   $directoryRepository
     * @param DirectoryDataProvider $directoryDataProvider
     */
    public function __construct(FileRepository $fileRepository, DirectoryRepository $directoryRepository, DirectoryDataProvider $directoryDataProvider)
    {
        parent::__construct($fileRepository, $directoryRepository);
        $this->directoryProvider = $directoryDataProvider;
    }

    /**
     * @param int   $targetDirId - destination folder id
     * @param array $filesIds    - list of files ids to move
     * @param array $foldersIds  - list of folders ids to move
     *
     * @return bool
     * @throws CopyMoveSelectionException
     */
    public function move($targetDirId, array $filesIds, array $foldersIds = array())
    {
        if (empty($filesIds) && empty($foldersIds)) {
            return false;
        }

        $targetDir = $this->getDirectoryById($targetDirId);

        foreach ($filesIds as $fileId) {
            $file = $this->getFileById($fileId);
            $file->setDirectory($targetDir);
        }

        if (!empty($foldersIds)) {
            $targetDirParents = $this->directoryProvider->getDirectoryParentsList($targetDir, false);

            foreach ($foldersIds as $dirId) {
                $dir = $this->getDirectoryById($dirId);

                if ($dir === $targetDir) {
                    throw new CopyMoveSelectionException('Try to move dir to it self: ' . $dir->getName());
                } else if (in_array($dir, $targetDirParents)) {
                    throw new CopyMoveSelectionException('Try to move parent dir to child dir: ' . $dir->getName());
                }

                $dir->setParent($targetDir);
            }
        }

        return true;
    }
}