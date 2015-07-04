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

use RI\FileManagerBundle\DataProvider\DirectoryDataProvider;
use RI\FileManagerBundle\Entity\Directory;
use RI\FileManagerBundle\Entity\DirectoryRepository;
use RI\FileManagerBundle\Entity\File;
use RI\FileManagerBundle\Entity\FileRepository;
use RI\FileManagerBundle\Model\Selection\MoveSelectionModel;
use RI\FileManagerBundle\Tests\BaseTestCase;

class MoveSelectionModelTest extends BaseTestCase
{
    const DIR_ID_1 = 1;
    const DIR_ID_1_1 = 3;
    const DIR_ID_2 = 2;

    const FILE_ID_1 = 1;
    const FILE_ID_2 = 2;

    /**
     * @var array
     */
    private $files = array();

    /**
     * @var array
     */
    private $directories = array();

    /**
     * @var MoveSelectionModel
     */
    private $moveSelectionModel;

    /**
     * @var FileRepository
     */
    private $fileRepositoryMock;

    /**
     * @var DirectoryRepository
     */
    private $directoryRepositoryMock;

    /**
     * @var DirectoryDataProvider
     */
    private $directoryDataProviderMock;

    public function setUp()
    {
        $this->fileRepositoryMock = $this->getMockBuilder('RI\FileManagerBundle\Entity\FileRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->directoryRepositoryMock = $this->getMockBuilder('RI\FileManagerBundle\Entity\DirectoryRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->directoryDataProviderMock = $this->getMockBuilder('RI\FileManagerBundle\DataProvider\DirectoryDataProvider')
            ->disableOriginalConstructor()
            ->getMock();

        $this->moveSelectionModel = new MoveSelectionModel($this->fileRepositoryMock, $this->directoryRepositoryMock, $this->directoryDataProviderMock);
    }

    /**
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::move
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::getDirectoryById
     *
     * @expectedException \RI\FileManagerBundle\Exceptions\CopyMoveSelectionException
     */
    public function testMove_ShouldReturnException_IfNoDestinationDir()
    {
        $this->directoryRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(null)
            ->will($this->returnValue(null));

        $this->moveSelectionModel->move(null, array(self::FILE_ID_1), array());
    }

    /**
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::move
     */
    public function testMove_ShouldReturnFalse_IfNoFilesAndDirectories()
    {
        $this->assertFalse($this->moveSelectionModel->move(self::DIR_ID_1, array(), array()));
    }

    /**
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::move
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::getDirectoryById
     *
     * @expectedException \RI\FileManagerBundle\Exceptions\CopyMoveSelectionException
     */
    public function testMove_ShouldThrowException_IfDestinationFolderIsChildOfMovedFolder()
    {
        $this->setMocks();

        $this->directoryRepositoryMock
            ->expects($this->at(0))
            ->method('find')
            ->with(self::DIR_ID_1_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1_1]));

        $this->directoryRepositoryMock
            ->expects($this->at(1))
            ->method('find')
            ->with(self::DIR_ID_2)
            ->will($this->returnValue($this->directories[self::DIR_ID_2]));

        $this->directoryRepositoryMock
            ->expects($this->at(2))
            ->method('find')
            ->with(self::DIR_ID_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1]));

        $this->directoryDataProviderMock
            ->expects($this->once())
            ->method('getDirectoryParentsList')
            ->with($this->directories[self::DIR_ID_1_1])
            ->will($this->returnValue(array($this->directories[self::DIR_ID_1])));

        $this->moveSelectionModel->move(self::DIR_ID_1_1, array(), array(self::DIR_ID_2, self::DIR_ID_1));
    }

    /**
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::move
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::getDirectoryById
     *
     * @expectedException \RI\FileManagerBundle\Exceptions\CopyMoveSelectionException
     */
    public function testMove_ShouldThrowException_IfDestinationFolderIsInSelection()
    {
        $this->setMocks();

        $this->directoryRepositoryMock
            ->expects($this->at(0))
            ->method('find')
            ->with(self::DIR_ID_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1]));

        $this->directoryRepositoryMock
            ->expects($this->at(1))
            ->method('find')
            ->with(self::DIR_ID_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1]));

        $this->directoryDataProviderMock
            ->expects($this->once())
            ->method('getDirectoryParentsList')
            ->with($this->directories[self::DIR_ID_1])
            ->will($this->returnValue(array()));

        $this->moveSelectionModel->move(self::DIR_ID_1, array(), array(self::DIR_ID_1));
    }

    /**
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::move
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::getDirectoryById
     * @covers RI\FileManagerBundle\Model\MoveSelectionModel::getFileById
     */
    public function testMove_ShouldReturnTrue()
    {
        $this->setMocks();

        $this->directoryRepositoryMock
            ->expects($this->at(0))
            ->method('find')
            ->with(self::DIR_ID_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1]));

        $this->directoryRepositoryMock
            ->expects($this->at(1))
            ->method('find')
            ->with(self::DIR_ID_2)
            ->will($this->returnValue($this->directories[self::DIR_ID_2]));

        $this->fileRepositoryMock
            ->expects($this->at(0))
            ->method('find')
            ->with(self::FILE_ID_1)
            ->will($this->returnValue($this->files[self::FILE_ID_1]));
        $this->fileRepositoryMock
            ->expects($this->at(1))
            ->method('find')
            ->with(self::FILE_ID_2)
            ->will($this->returnValue($this->files[self::FILE_ID_2]));

        $this->directoryDataProviderMock
            ->expects($this->once())
            ->method('getDirectoryParentsList')
            ->with($this->directories[self::DIR_ID_1])
            ->will($this->returnValue(array()));

        $result = $this->moveSelectionModel->move(self::DIR_ID_1, array(self::FILE_ID_1, self::FILE_ID_2), array(self::DIR_ID_2));

        $this->assertTrue($result);
        $this->assertEquals($this->directories[self::DIR_ID_1], $this->files[self::FILE_ID_1]->getDirectory());
        $this->assertEquals($this->directories[self::DIR_ID_1], $this->files[self::FILE_ID_2]->getDirectory());
        $this->assertEquals($this->directories[self::DIR_ID_1], $this->directories[self::DIR_ID_2]->getParent());
    }


    /**
     * Set necessary mocks
     */
    private function setMocks()
    {
        $directory1 = new Directory();
        $directory1->setName('first');
        $directory2 = new Directory();
        $directory2->setName('second');
        $directory3 = new Directory();
        $directory3->setName('subfirst');

        $this->directories = array(
            self::DIR_ID_1 => $directory1,
            self::DIR_ID_2 => $directory2,
            self::DIR_ID_1_1 => $directory3,
        );

        $file1 = new File();
        $file1->setDirectory($directory1);
        $file2 = new File();
        $file2->setDirectory($directory2);

        $this->files[self::FILE_ID_1] = $file1;
        $this->files[self::FILE_ID_2] = $file2;
    }


}
 