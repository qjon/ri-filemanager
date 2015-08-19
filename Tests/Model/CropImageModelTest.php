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

use RI\FileManagerBundle\Model\CropImageModel;
use RI\FileManagerBundle\Tests\BaseTestCase;

class CropImageModelTest extends BaseTestCase
{
    const ROOT = '/../web';

    private $entityManagerMock;
    private $rootDirMock;
    private $cropImageModer;

    private $fileMock;

    private $path;

    public function setUp()
    {
        $this->entityManagerMock = $this->createMock('Doctrine\ORM\EntityManager');
        $this->rootDirMock = __DIR__ . self::ROOT;

        $this->cropImageModer = new CropImageModel($this->entityManagerMock, $this->rootDirMock);

        $this->fileMock = $this->prepareFile();
    }

    public function tearDown()
    {
        unlink($this->path);
    }


    /**
     * @covers RI\FileManagerBundle\Model\CropImageModel::__construct
     * @covers RI\FileManagerBundle\Model\CropImageModel::setFile
     */
    public function testSetFile()
    {
        $filename = basename($this->path);
        $this->fileMock->expects($this->once())
            ->method('getPath')
            ->will($this->returnValue($filename));

        $this->cropImageModer->setFile($this->fileMock);
    }


    /**
     * @covers RI\FileManagerBundle\Model\CropImageModel::setFile
     * @covers RI\FileManagerBundle\Model\CropImageModel::crop
     */
    public function testCrop()
    {
        $x = 0;
        $y = 0;
        $width = 500;
        $height = 300;

        $this->entityManagerMock->expects($this->once())
            ->method('flush');

        $filename = basename($this->path);
        $this->fileMock->expects($this->exactly(2))
            ->method('getPath')
            ->will($this->returnValue($filename));

        $uploadedFileParametersModel = $this->createMock('RI\FileManagerBundle\Model\UploadedFileParametersModel');

        $this->fileMock->expects($this->exactly(2))
            ->method('getParams')
            ->will($this->returnValue($uploadedFileParametersModel));

        $uploadedFileParametersModel->expects($this->once())
            ->method('setWidth')
            ->with($width);

        $uploadedFileParametersModel->expects($this->once())
            ->method('setHeight')
            ->with($height);

        $this->cropImageModer->setFile($this->fileMock);
        $result = $this->cropImageModer->crop($x, $y, $width, $height);

        $this->assertTrue($result);
    }

    /**
     * @covers RI\FileManagerBundle\Model\CropImageModel::setFile
     * @covers RI\FileManagerBundle\Model\CropImageModel::crop
     */
    public function testCrop_ShouldReturnFalse()
    {
        $x = 0;
        $y = 0;
        $width = -500;
        $height = 300;

        $filename = basename($this->path);
        $this->fileMock->expects($this->exactly(2))
            ->method('getPath')
            ->will($this->returnValue($filename));

        $this->fileMock->expects($this->once()  )
            ->method('getParams')
            ->will($this->throwException(new \Exception('exception message')));

        $this->cropImageModer->setFile($this->fileMock);
        $result = $this->cropImageModer->crop($x, $y, $width, $height);

        $this->assertFalse($result);
    }


    /**
     * Prepare file mock
     *
     * @return mixed
     */
    private function prepareFile()
    {
        $this->path = $this->prepareTestImage('abc.jpg');

        $file = $this->createMock('RI\FileManagerBundle\Entity\File');

        return $file;
    }
}
