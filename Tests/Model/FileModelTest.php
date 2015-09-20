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

use RI\FileManagerBundle\Model\FileModel;
use RI\FileManagerBundle\Model\UploadedFileParametersModel;
use RI\FileManagerBundle\Tests\BaseTestCase;
use Symfony\Component\HttpFoundation\File\Exception\UploadException;

class FileModelTest extends BaseTestCase
{
    const ROOT = '/../root';

    private $entityManagerMock;
    private $uploadDirectoryManagerMock;
    private $rootDirMock;
    private $doResizeMock;
    private $maxResizeWithMock;
    private $uploadedFileMimeTypeMock;

    /**
     * @var FileModel
     */
    private $fileModel;


    public function setUp()
    {
        $this->entityManagerMock = $this->createMock('Doctrine\ORM\EntityManager');
        $this->uploadDirectoryManagerMock = $this->createMock('RI\FileManagerBundle\Manager\UploadDirectoryManager');
        $this->rootDirMock = __DIR__ . self::ROOT;
        $this->doResizeMock = true;
        $this->maxResizeWithMock = 1024;
        $this->uploadedFileMimeTypeMock = array();

        $this->fileModel = new FileModel($this->entityManagerMock, $this->uploadDirectoryManagerMock, $this->rootDirMock, $this->doResizeMock, $this->maxResizeWithMock, $this->uploadedFileMimeTypeMock);
    }


    /**
     * @covers RI\FileManagerBundle\Model\FileModel::__construct
     * @covers RI\FileManagerBundle\Model\FileModel::save
     * @covers RI\FileManagerBundle\Model\FileModel::isAllowMimeTypes
     */
    public function testSave()
    {
        $filename = 'abc.jpg';
        $newFileName = 'abc_1.jpg';
        $newFileDir = '/../web/';
        $newFilePath = $newFileDir . $newFileName;
        $size = 123098;
        $mime = 'image/jpg';

        $uploadedFileMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->enableOriginalConstructor()
            ->setConstructorArgs([tempnam(sys_get_temp_dir(), ''), 'dummy'])
            ->getMock();

        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');

        $this->uploadDirectoryManagerMock->expects($this->once())
            ->method('getNewPath')
            ->with($filename)
            ->will($this->returnValue($newFilePath));

        $uploadedFileMock->expects($this->once())
            ->method('getSize')
            ->will($this->returnValue($size));

        $uploadedFileMock->expects($this->once())
            ->method('getMimeType')
            ->will($this->returnValue($mime));

        $uploadedFileMock->expects($this->once())
            ->method('move')
            ->with(__DIR__ . self::ROOT . '/../web/../web', $newFileName)
            ->will($this->returnValue(true));

        $this->entityManagerMock->expects($this->once())
            ->method('persist');

        $this->entityManagerMock->expects($this->once())
            ->method('flush');


        $file = $this->fileModel->save($filename, $uploadedFileMock, $directoryMock);

        $this->assertEquals($filename, $file->getName());
        $this->assertEquals($directoryMock, $file->getDirectory());
        $this->assertEquals($newFilePath, $file->getPath());
        $this->assertEquals('da8d5422654f5648a91a2d0d5cd49178', $file->getChecksum());
        $this->assertEquals($size, $file->getParams()->getSize());
        $this->assertEquals(800, $file->getParams()->getWidth());
        $this->assertEquals(480, $file->getParams()->getHeight());
    }

    /**
     * @covers RI\FileManagerBundle\Model\FileModel::__construct
     * @covers RI\FileManagerBundle\Model\FileModel::save
     * @covers RI\FileManagerBundle\Model\FileModel::isAllowMimeTypes
     */
    public function testSave_ShouldUploadFileIfIsAllowedMimeType()
    {
        $filename = 'abc.jpg';
        $newFileName = 'abc_1.jpg';
        $newFileDir = '/../web/';
        $newFilePath = $newFileDir . $newFileName;
        $size = 123098;
        $mime = 'image/jpeg';

        $uploadedFileMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->enableOriginalConstructor()
            ->setConstructorArgs([tempnam(sys_get_temp_dir(), ''), 'dummy'])
            ->getMock();

        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');

        $this->uploadDirectoryManagerMock->expects($this->once())
            ->method('getNewPath')
            ->with($filename)
            ->will($this->returnValue($newFilePath));

        $uploadedFileMock->expects($this->once())
            ->method('getSize')
            ->will($this->returnValue($size));

        $uploadedFileMock->expects($this->exactly(2))
            ->method('getMimeType')
            ->will($this->returnValue($mime));

        $uploadedFileMock->expects($this->once())
            ->method('move')
            ->with(__DIR__ . self::ROOT . '/../web/../web', $newFileName)
            ->will($this->returnValue(true));

        $this->entityManagerMock->expects($this->once())
            ->method('persist');

        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $fileModel = new FileModel($this->entityManagerMock, $this->uploadDirectoryManagerMock, $this->rootDirMock, $this->doResizeMock, $this->maxResizeWithMock, array('image/jpeg', 'application/pdf'));

        $file = $fileModel->save($filename, $uploadedFileMock, $directoryMock);

        $this->assertEquals($filename, $file->getName());
        $this->assertEquals($directoryMock, $file->getDirectory());
        $this->assertEquals($newFilePath, $file->getPath());
        $this->assertEquals('da8d5422654f5648a91a2d0d5cd49178', $file->getChecksum());
        $this->assertEquals($size, $file->getParams()->getSize());
        $this->assertEquals(800, $file->getParams()->getWidth());
        $this->assertEquals(480, $file->getParams()->getHeight());
    }


    /**
     * @covers RI\FileManagerBundle\Model\FileModel::save
     * @covers RI\FileManagerBundle\Model\FileModel::isAllowMimeTypes
     *
     * @expectedException Symfony\Component\HttpFoundation\File\Exception\UploadException
     */
    public function testSave_ShouldThrowException()
    {
        $fileModel = new FileModel($this->entityManagerMock, $this->uploadDirectoryManagerMock, $this->rootDirMock, $this->doResizeMock, $this->maxResizeWithMock, array('application/pdf'));

        $filename = 'abc.jpg';
        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');
        $uploadedFileMock = $this->getMockBuilder('Symfony\Component\HttpFoundation\File\UploadedFile')
            ->enableOriginalConstructor()
            ->setConstructorArgs([tempnam(sys_get_temp_dir(), ''), 'dummy', 'image/jpg'])
            ->getMock();

        $fileModel->save($filename, $uploadedFileMock, $directoryMock);
    }


    /**
     * @covers RI\FileManagerBundle\Model\FileModel::createFileParams
     * @dataProvider createFileParamsDataProvider
     */
    public function testCreateFileParams($path, $width, $height, $mime, $size)
    {
        /** @var UploadedFileParametersModel $uploadedFileParamtersModel */
        $uploadedFileParamtersModel = $this->fileModel->createFileParams($size, $mime, __DIR__ . '/../web/' . $path);

        $this->assertEquals($width, $uploadedFileParamtersModel->getWidth());
        $this->assertEquals($height, $uploadedFileParamtersModel->getHeight());
        $this->assertEquals($mime, $uploadedFileParamtersModel->getMime());
        $this->assertEquals($size, $uploadedFileParamtersModel->getSize());
    }


    /**
     * @return array
     */
    public function createFileParamsDataProvider()
    {
        return array(
            array('abc.jpg', 800, 480, 'image/jpeg', 33316),
            array('star_wars_1.jpg', 1600, 1066, 'image/jpeg', 194617),
            array('star_wars_2.jpg', 800, 1200, 'image/jpeg', 196898)
        );
    }

    /**
     * @covers RI\FileManagerBundle\Model\FileModel::resizeImage
     * @dataProvider resizeImageDataProvider
     */
    public function testResizeImage($filename, $expectedWidth, $expectedHeight)
    {
        $path = $this->prepareTestImage($filename);

        $this->fileModel->resizeImage($path);
        $imageData = getimagesize($path);

        $this->assertEquals($expectedWidth, $imageData[0]);
        $this->assertEquals($expectedHeight, $imageData[1]);

        unlink($path);
    }


    /**
     * @return array
     */
    public function resizeImageDataProvider()
    {
        return array(
            array('abc.jpg', 800, 480),
            array('star_wars_1.jpg', 1024, 682),
            array('star_wars_2.jpg', 682, 1024)
        );
    }


    /**
     * @covers RI\FileManagerBundle\Model\FileModel::getChecksum
     */
    public function testGetChecksum()
    {
        $this->assertEquals('da8d5422654f5648a91a2d0d5cd49178', $this->fileModel->getChecksum('/abc.jpg'));
    }
}
