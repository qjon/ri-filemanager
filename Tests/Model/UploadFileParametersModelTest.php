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

use RI\FileManagerBundle\Model\UploadedFileParametersModel;

/**
 * Unit tests for UploadDirectoryManager
 *
 * @package RI\FileManagerBundle\Tests\Controller
 */
class UploadFileParametersModelTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UploadedFileParametersModel
     */
    private $uploadedFileParametersModel;

    /**
     * @var \ReflectionClass
     */
    private $uploadedFileParametersModelReflection;

    /**
     * Set up objects
     */
    public function setUp()
    {
        $this->uploadedFileParametersModel = new UploadedFileParametersModel();
        $this->uploadedFileParametersModelReflection = new \ReflectionClass('RI\FileManagerBundle\Model\UploadedFileParametersModel');
    }

    /**
     * @covers: RI\FileManagerBundle\Model\UploadedFileParametersModel::getResourceSizeUnit
     */
    public function testGetResourceSizeUnit()
    {
        $data = array(
            0 => UploadedFileParametersModel::B_UNIT,
            1 => UploadedFileParametersModel::KB_UNIT,
            2 => UploadedFileParametersModel::MB_UNIT
        );

        $method = $this->uploadedFileParametersModelReflection->getMethod('getResourceSizeUnit');
        $method->setAccessible(true);

        foreach ($data as $input => $expected) {
            $this->assertEquals($expected, $method->invokeArgs($this->uploadedFileParametersModel, array($input)));
        }
    }

    /**
     * @covers: RI\FileManagerBundle\Model\UploadedFileParametersModel::normalizeSize
     */
    public function testNormalizeSize()
    {
        $data = array(
            1150 => '1.12 ' . UploadedFileParametersModel::KB_UNIT,
            2423231 => '2.31 ' . UploadedFileParametersModel::MB_UNIT,
            2 => '2 ' . UploadedFileParametersModel::B_UNIT
        );

        $method = $this->uploadedFileParametersModelReflection->getMethod('normalizeSize');
        $method->setAccessible(true);

        foreach ($data as $input => $expected) {
            $this->assertEquals($expected, $method->invokeArgs($this->uploadedFileParametersModel, array($input)));
        }
    }

    /**
     * @covers: RI\FileManagerBundle\Model\UploadedFileParametersModel::setSize
     * @covers: RI\FileManagerBundle\Model\UploadedFileParametersModel::getSize
     * @covers: RI\FileManagerBundle\Model\UploadedFileParametersModel::getSizeNormalize
     */
    public function testSetSize()
    {
        $size = 2423231;
        $this->uploadedFileParametersModel->setSize($size);

        $this->assertEquals($size, $this->uploadedFileParametersModel->getSize());
        $this->assertEquals('2.31 ' . UploadedFileParametersModel::MB_UNIT, $this->uploadedFileParametersModel->getSizeNormalize());
    }


    /**
     * @covers: RI\FileManagerBundle\Model\UploadedFileParametersModel::serialize
     */
    public function testSerialize()
    {
        $width = 200;
        $height = 100;
        $size = 51203;
        $mime = 'image/jpg';

        $this->uploadedFileParametersModel->setWidth($width);
        $this->uploadedFileParametersModel->setHeight($height);
        $this->uploadedFileParametersModel->setSize($size);
        $this->uploadedFileParametersModel->setMime($mime);

        $expectedArray = array(
            'width' => $width,
            'height' => $height,
            'mime' => $mime,
            'size' => $size,
            'sizeNormalized' => '50 KB'
        );
        $this->assertEquals(serialize($expectedArray), $this->uploadedFileParametersModel->serialize());
    }


    /**
     * @covers: RI\FileManagerBundle\Model\UploadedFileParametersModel::unserialize
     */
    public function testUnserialize()
    {
        $width = 200;
        $height = 100;
        $size = 51203;
        $mime = 'image/jpg';

        $data = array(
            'width' => $width,
            'height' => $height,
            'mime' => $mime,
            'size' => $size,
            'sizeNormalized' => '50 KB'
        );

        $this->uploadedFileParametersModel->unserialize(serialize($data));

        $this->assertEquals($width, $this->uploadedFileParametersModel->getWidth());
        $this->assertEquals($height, $this->uploadedFileParametersModel->getHeight());
        $this->assertEquals($size, $this->uploadedFileParametersModel->getSize());
        $this->assertEquals($mime, $this->uploadedFileParametersModel->getMime());
        $this->assertEquals('50 KB', $this->uploadedFileParametersModel->getSizeNormalize());
    }

}
