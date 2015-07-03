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

/**
 * Unit tests for UploadDirectoryManager
 *
 * @package RI\FileManagerBundle\Tests\Controller
 */
class UploadDirectoryManagerTest extends \PHPUnit_Framework_TestCase
{
    const FILENAME = 'hello_world.jpg';
    const NEW_FILENAME = '790f1bfea8585c9bc2e7b6267cb212e1.jpg';

    /**
     * @var UploadDirectoryManager
     */
    protected $uploadDirectoryManager;

    /**
     * @var UploadDirectoryManager
     */
    protected $uploadDirectoryManagerReflection;

    /**
     * Prepare objects
     */
    public function setUp()
    {
        $this->uploadDirectoryManager = new UploadDirectoryManager(__DIR__ . '/../../../../../../../web/uploads');
        $this->uploadDirectoryManagerReflection = new \ReflectionClass('RI\FileManagerBundle\Manager\UploadDirectoryManager');
    }

    /**
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::generateNewFileName
     */
    public function testGenerateNewFileName()
    {
        $newFilename = $this->generateNewFilename();

        $this->assertEquals(self::NEW_FILENAME, $newFilename);
    }

    /**
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::createDestinationDir
     * @covers: RI\FileManagerBundle\Manager\UploadDirectoryManager::generateNewFileName
     */
    public function testCreateDestinationDir()
    {
        $newFilename = $this->generateNewFilename();
        $date = new \DateTime();

        $method = $this->uploadDirectoryManagerReflection->getMethod('createDestinationDir');
        $method->setAccessible(true);

        $subDirExpected = sprintf('%s/%s/%s/%s/7/79', UploadDirectoryManager::uploadDir, $date->format("y"), $date->format("m"), $date->format("d"));
        $subDir = $method->invokeArgs($this->uploadDirectoryManager, array($newFilename));

        $this->assertEquals($subDirExpected, $subDir);
    }

    /**
     * Return new filename
     *
     * @return string
     */
    private function generateNewFilename()
    {
        $method = $this->uploadDirectoryManagerReflection->getMethod('generateNewFileName');
        $method->setAccessible(true);
        $uploadedFile = $photo = new UploadedFile(__DIR__ . '/hello_world.jpg', self::FILENAME, 'image/jpg', 123);

        return $method->invokeArgs($this->uploadDirectoryManager, array($uploadedFile->getClientOriginalName(), $uploadedFile->getExtension()));
    }
}
