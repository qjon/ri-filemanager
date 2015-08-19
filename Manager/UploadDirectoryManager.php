<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Manager;

use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * Class UploadDirectoryManager is responsible for creating new file name and path
 *
 * @package RI\FileManagerBundle\Managers
 */
class UploadDirectoryManager
{
    const DEFAULT_FOLDER_RIGHTS = 0777;

    /**
     * @var string
     */
    protected $uploadDir = '/uploads';


    /**
     * Absolute path to upload dir
     *
     * @var string
     */
    protected $absoluteUploadDirPath;

    /**
     * @param string $kernelRootDir
     * @param string $uploadsDir
     */
    public function __construct($kernelRootDir, $uploadsDir)
    {
        $this->absoluteUploadDirPath = $this->getAbsoluteUploadDirPath($kernelRootDir);
        $this->uploadDir = $uploadsDir;
    }

    /**
     * @return string
     */
    public function getAbsoluteUploadDir()
    {
        return $this->absoluteUploadDirPath;
    }

    /**
     * Create and return new file path with new filename
     *
     * @param string $filename
     *
     * @return string
     */
    public function getNewPath($filename)
    {
        $newFileName = $this->generateNewFileName($filename);
        $path = $this->createDestinationDir($newFileName);
        while (file_exists($this->absoluteUploadDirPath . $path . '/' . $newFileName)) {
            $newFileName = $this->generateNewFileName($newFileName);
        }

        return sprintf('%s/%s', $path, $newFileName);
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    private function generateNewFileName($filename)
    {
        $splitFilename = explode('.', $filename);
        $extension = array_pop($splitFilename);

        return md5($filename) . '.' . $extension;
    }

    /**
     * @param string $newFileName filename create by generateNewFileName
     *
     * @return string
     */
    private function createDestinationDir($newFileName)
    {
        $date = new \DateTime();

        $subDir = sprintf('/%s/%s/%s/%s/%s', $date->format("y"), $date->format("m"), $date->format("d"), substr($newFileName, 0, 1), substr($newFileName, 0, 2));

        if (!is_dir($this->absoluteUploadDirPath . $this->uploadDir . $subDir)) {
            mkdir($this->absoluteUploadDirPath . $this->uploadDir . $subDir, self::DEFAULT_FOLDER_RIGHTS, true);
        }

        return $this->uploadDir . $subDir;
    }


    /**
     * @param $kernelRootDir
     */
    private function getAbsoluteUploadDirPath($kernelRootDir)
    {
        $dirs = explode('/', $kernelRootDir);
        array_pop($dirs);
        return '/' . implode($dirs) . '/web';
    }
} 