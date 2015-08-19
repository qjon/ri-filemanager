<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Tests\DataProvider;

use Doctrine\ORM\EntityManager;
use RI\FileManagerBundle\DataProvider\FileDataProvider;
use RI\FileManagerBundle\Tests\BaseTestCase;

/**
 * Class FileDataProviderTest
 * @package RI\FileManagerBundle\Tests\DataProvider
 */
class FileDataProviderTest extends BaseTestCase
{

    /**
     * @var EntityManager
     */
    private $entityManagerMock;

    /**
     * @var FileDataProvider
     */
    private $fileDataProvider;

    public function setUp()
    {
        $this->entityManagerMock = $this->createMock('Doctrine\ORM\EntityManager');
        $this->fileDataProvider = new FileDataProvider($this->entityManagerMock);
    }


    /**
     * @covers RI\FileManagerBundle\DataProvider\DataProviderAbstract::__construct
     * @covers RI\FileManagerBundle\DataProvider\DataProviderAbstract::getEntityManager
     */
    public function testGetEntityManager()
    {
        $this->assertEquals($this->entityManagerMock, $this->fileDataProvider->getEntityManager());
    }


    /**
     * @covers RI\FileManagerBundle\DataProvider\FileDataProvider::getRootDirectoryFiles
     */
    public function testGetRootDirectoryFiles()
    {
        $directoryId = null;

        $files = array(
            $this->createFileMock(7, $directoryId, 'File 1', 'file/one.jpg', 'image/jpg', 1200, 800),
            $this->createFileMock(10, $directoryId, 'File 2', 'file/two.png', 'image/png', 800, 1600),
        );

        $expectedData = array(
            array(
                'id' => 7,
                'dirId' => 0,
                'name' => 'File 1',
                'src' => 'file/one.jpg',
                'mime' => 'image/jpg',
                'width' => 1200,
                'height' => 800
            ),
            array(
                'id' => 10,
                'dirId' => 0,
                'name' => 'File 2',
                'src' => 'file/two.png',
                'mime' => 'image/png',
                'width' => 800,
                'height' => 1600
            )
        );

        $repositoryMock = $this->createMock('RI\FileManagerBundle\Entity\FileRepository');

        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(array('directory' => $directoryId))
            ->will($this->returnValue($files));

        $this->entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with('RIFileManagerBundle:File')
            ->will($this->returnValue($repositoryMock));

        $this->assertEquals($expectedData, $this->fileDataProvider->getRootDirectoryFiles($directoryId));
    }

    /**
     * @covers RI\FileManagerBundle\DataProvider\FileDataProvider::getFilesFromDirectory
     */
    public function testGetFilesFromDirectory()
    {
        $directoryId = 8;

        $files = array(
            $this->createFileMock(7, $directoryId, 'File 1', 'file/one.jpg', 'image/jpg', 1200, 800),
            $this->createFileMock(10, $directoryId, 'File 2', 'file/two.png', 'image/png', 800, 1600),
        );

        $expectedData = array(
            array(
                'id' => 7,
                'dirId' => $directoryId,
                'name' => 'File 1',
                'src' => 'file/one.jpg',
                'mime' => 'image/jpg',
                'width' => 1200,
                'height' => 800
            ),
            array(
                'id' => 10,
                'dirId' => $directoryId,
                'name' => 'File 2',
                'src' => 'file/two.png',
                'mime' => 'image/png',
                'width' => 800,
                'height' => 1600
            )
        );

        $repositoryMock = $this->createMock('RI\FileManagerBundle\Entity\FileRepository');

        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(array('directory' => $directoryId))
            ->will($this->returnValue($files));

        $this->entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with('RIFileManagerBundle:File')
            ->will($this->returnValue($repositoryMock));

        $this->assertEquals($expectedData, $this->fileDataProvider->getFilesFromDirectory($directoryId));
    }

    /**
     * @covers RI\FileManagerBundle\DataProvider\FileDataProvider::convertFileEntityToArray
     */
    public function testConvertFileEntityToArray()
    {
        $id = 1;
        $dirId = 10;
        $name = 'Filename';
        $src = '/path/to/file';
        $mime = 'image/jpg';
        $width = 1200;
        $height = 800;

        $fileMock = $this->createFileMock($id, $dirId, $name, $src, $mime, $width, $height);


        $expectData = array(
            'id' => $id,
            'dirId' => $dirId,
            'name' => $name,
            'src' => $src,
            'mime' => $mime,
            'width' => $width,
            'height' => $height
        );

        $this->assertEquals($expectData, $this->fileDataProvider->convertFileEntityToArray($fileMock));
    }


    /**
     * @param $id
     * @param $dirId
     * @param $name
     * @param $src
     * @param $mime
     * @param $width
     * @param $height
     *
     * @return mixed
     */
    private function createFileMock($id, $dirId, $name, $src, $mime, $width, $height)
    {
        $fileMock = $this->createMock('RI\FileManagerBundle\Entity\File');
        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');
        $uploadedFileParameterModelMock = $this->createMock('RI\FileManagerBundle\Model\UploadedFileParametersModel');

        $fileMock->expects($this->once())
            ->method('getParams')
            ->will($this->returnValue($uploadedFileParameterModelMock));

        $fileMock->expects($this->once())
            ->method('getDirectory')
            ->will($this->returnValue($directoryMock));

        $fileMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($id));

        $directoryMock->expects($this->once())
            ->method('getId')
            ->will($this->returnValue($dirId));

        $fileMock->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($name));

        $fileMock->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue($src));

        $uploadedFileParameterModelMock->expects($this->once())
            ->method('getMime')
            ->will($this->returnValue($mime));

        $uploadedFileParameterModelMock->expects($this->once())
            ->method('getWidth')
            ->will($this->returnValue($width));

        $uploadedFileParameterModelMock->expects($this->once())
            ->method('getHeight')
            ->will($this->returnValue($height));

        return $fileMock;
    }
}
