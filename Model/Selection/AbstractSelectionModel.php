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

use RI\FileManagerBundle\Entity\Directory;
use RI\FileManagerBundle\Entity\DirectoryRepository;
use RI\FileManagerBundle\Entity\File;
use RI\FileManagerBundle\Entity\FileRepository;
use RI\FileManagerBundle\Exceptions\CopyMoveSelectionException;

abstract class AbstractSelectionModel
{
    /**
     * @var FileRepository
     */
    protected $fileRepository;

    /**
     * @var DirectoryRepository
     */
    protected $directoryRepository;


    /**
     * @param FileRepository        $fileRepository
     * @param DirectoryRepository   $directoryRepository
     */
    public function __construct(FileRepository $fileRepository, DirectoryRepository $directoryRepository)
    {
        $this->fileRepository = $fileRepository;
        $this->directoryRepository = $directoryRepository;
    }

    /**
     * @param int $id
     *
     * @return File
     * @throws CopyMoveSelectionException
     */
    public function getFileById($id)
    {
        $file = $this->fileRepository->find($id);

        if (empty($file)) {
            throw new CopyMoveSelectionException('File not exist');
        }

        return $file;
    }

    /**
     * @param int $id
     *
     * @return Directory
     * @throws CopyMoveSelectionException
     */
    public function getDirectoryById($id)
    {
        $directory = $this->directoryRepository->find($id);

        if (empty($directory)) {
            throw new CopyMoveSelectionException('Directory not exist');
        }

        return $directory;
    }
}