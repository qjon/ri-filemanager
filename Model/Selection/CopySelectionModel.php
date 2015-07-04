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
use RI\FileManagerBundle\DataProvider\DirectoryDataProvider;
use RI\FileManagerBundle\Entity\Directory;
use RI\FileManagerBundle\Entity\DirectoryRepository;
use RI\FileManagerBundle\Entity\File;
use RI\FileManagerBundle\Entity\FileRepository;
use RI\FileManagerBundle\Exceptions\CopyMoveSelectionException;
use RI\FileManagerBundle\Manager\UploadDirectoryManager;

class CopySelectionModel extends AbstractSelectionModel
{

    /**
     * @var DirectoryDataProvider
     */
    private $directoryProvider;

    /**
     * @var EntityManager
     */
    private $entityManager;

    /**
     * @var UploadDirectoryManager
     */
    private $uploadDirectoryManager;

    /**
     * @param FileRepository         $fileRepository
     * @param DirectoryRepository    $directoryRepository
     * @param DirectoryDataProvider  $directoryDataProvider
     * @param EntityManager          $entityManager
     * @param UploadDirectoryManager $uploadDirectoryManager
     */
    public function __construct(FileRepository $fileRepository, DirectoryRepository $directoryRepository, DirectoryDataProvider $directoryDataProvider, EntityManager $entityManager, UploadDirectoryManager $uploadDirectoryManager)
    {
        parent::__construct($fileRepository, $directoryRepository);
        $this->directoryProvider = $directoryDataProvider;
        $this->entityManager = $entityManager;
        $this->uploadDirectoryManager = $uploadDirectoryManager;
    }

    /**
     * @param int   $targetDirId - destination folder id
     * @param array $filesIds    - list of files ids to copy
     * @param array $foldersIds  - list of folders ids to copy
     *
     * @return bool
     * @throws CopyMoveSelectionException
     */
    public function copy($targetDirId, array $filesIds, array $foldersIds = array())
    {
        if (empty($filesIds) && empty($foldersIds)) {
            return false;
        }

        $targetDir = $this->getDirectoryById($targetDirId);

        $this->copyFolders($targetDir, $foldersIds);
        $this->copyFiles($targetDir, $filesIds);

        return true;
    }

    /**
     * @param Directory $targetDir
     * @param array     $filesIds
     *
     * @throws CopyMoveSelectionException
     */
    private function copyFiles(Directory $targetDir, array $filesIds = array())
    {
        foreach ($filesIds as $fileId) {
            $file = $this->getFileById($fileId);

            $this->copyFile($targetDir, $file);
        }
    }

    /**
     * @param Directory $targetDir
     * @param array     $foldersIds
     *
     * @throws CopyMoveSelectionException
     */
    private function copyFolders(Directory $targetDir, array $foldersIds = array())
    {
        if (!empty($foldersIds)) {
            $targetDirParents = $this->directoryProvider->getDirectoryParentsList($targetDir, false);

            foreach ($foldersIds as $dirId) {
                $dir = $this->getDirectoryById($dirId);

                if ($dir === $targetDir) {
                    throw new CopyMoveSelectionException('Try to move dir to it self: ' . $dir->getName());
                } else {
                    if (in_array($dir, $targetDirParents)) {
                        throw new CopyMoveSelectionException('Try to move parent dir to child dir: ' . $dir->getName());
                    }
                }

                $this->copyFolderRecursive($targetDir, $dir);
            }
        }
    }

    /**
     * @param Directory $parentDir
     * @param Directory $dir
     */
    private function copyFolderRecursive(Directory $parentDir, Directory $dir)
    {
        $newDir = clone $dir;
        $newDir->setParent($parentDir);
        $this->entityManager->persist($newDir);

        $files = $this->fileRepository->fetchFromDir($dir);
        foreach ($files as $file) {
            $this->copyFile($newDir, $file);
        }

        /** @var Directory $child */
        foreach ($dir->getChildren() as $child) {
            $this->copyFolderRecursive($newDir, $child);
        }
    }

    /**
     * @param Directory $directory
     * @param File      $file
     *
     * @return File
     * @throws CopyMoveSelectionException
     */
    private function copyFile(Directory $directory, File $file)
    {
        $newFile = clone($file);
        $newFile->setDirectory($directory);
        $newFile->setPath($this->copyFileOnDisk($file->getPath()));

        $this->entityManager->persist($newFile);

        return $newFile;
    }

    /**
     * @param $filePath
     *
     * @return string
     * @throws CopyMoveSelectionException
     */
    private function copyFileOnDisk($filePath)
    {
        $filename = basename($filePath);
        $newFileName = $this->uploadDirectoryManager->getNewPath($filename);

        $uploadDir = $this->uploadDirectoryManager->getAbsoluteUploadDir();
        if (!copy($uploadDir . $filePath, $uploadDir . $newFileName)) {
            throw new CopyMoveSelectionException('File not coppied ' . $filePath);
        }

        return $newFileName;
    }
}