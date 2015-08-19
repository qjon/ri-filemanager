<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Tests\Controller;

use RI\FileManagerBundle\Manager\UploadDirectoryManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * Unit tests for UploadDirectoryManager
 *
 * @package RI\FileManagerBundle\Tests\Controller
 */
class UploadDirectoryManagerTest extends \PHPUnit_Framework_TestCase
{
    const FILENAME = 'hello_world.jpg';
    const NEW_FILENAME = '790f1bfea8585c9bc2e7b6267cb212e1.jpg';
    const UPLOAD_DIR = '/upload';
    const KERNEL_DIR = '/tmp/kernel';

    /**
     * @var UploadDirectoryManager
     */
    protected $uploadDirectoryManager;


    /**
     * Prepare objects
     */
    public function setUp()
    {
        $this->uploadDirectoryManager = new UploadDirectoryManager(self::KERNEL_DIR, self::UPLOAD_DIR);
    }

    /**
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::__construct
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::getAbsoluteUploadDirPath
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::getAbsoluteUploadDir
     */
    public function testGetAbsoluteUploadDir()
    {
        $this->assertEquals('/tmp/web', $this->uploadDirectoryManager->getAbsoluteUploadDir());
    }

    /**
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::getNewPath
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::getAbsoluteUploadDirPath
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::generateNewFileName
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::createDestinationDir
     */
    public function testGetNewPath()
    {
        $date = new \DateTime();
        $expectedPath = sprintf('/upload/%s/%s/%s/7/79/790f1bfea8585c9bc2e7b6267cb212e1.jpg', $date->format('y'), $date->format('m'), $date->format('d'));

        $this->assertEquals($expectedPath, $this->uploadDirectoryManager->getNewPath('hello_world.jpg'));
    }

    /**
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::getNewPath
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::getAbsoluteUploadDirPath
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::generateNewFileName
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::createDestinationDir
     */
    public function testGetNewPath_IfFileExist()
    {
        $date = new \DateTime();
        $expectedPath = sprintf('/upload/%s/%s/%s/7/79/790f1bfea8585c9bc2e7b6267cb212e1.jpg', $date->format('y'), $date->format('m'), $date->format('d'));
        $expectedPathSecondFile = sprintf('/upload/%s/%s/%s/7/79/8b912a2bf1302f5a1170d3e57d8f8caf.jpg', $date->format('y'), $date->format('m'), $date->format('d'));

        $this->assertEquals($expectedPath, $this->uploadDirectoryManager->getNewPath('hello_world.jpg'));
        copy(__DIR__ . '/hello_world.jpg', sprintf('/tmp/web/upload/%s/%s/%s/7/79/790f1bfea8585c9bc2e7b6267cb212e1.jpg', $date->format('y'), $date->format('m'), $date->format('d')));
        $this->assertEquals($expectedPathSecondFile, $this->uploadDirectoryManager->getNewPath('hello_world.jpg'));
    }

    /**
     * Prepare objects
     */
    public function tearDown()
    {
        system('rm -rf /tmp/web');
    }
}

