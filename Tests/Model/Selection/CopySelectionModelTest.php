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

use Doctrine\ORM\EntityManager;
use RI\FileManagerBundle\DataProvider\DirectoryDataProvider;
use RI\FileManagerBundle\Entity\Directory;
use RI\FileManagerBundle\Entity\DirectoryRepository;
use RI\FileManagerBundle\Entity\File;
use RI\FileManagerBundle\Entity\FileRepository;
use RI\FileManagerBundle\Manager\UploadDirectoryManager;
use RI\FileManagerBundle\Model\Selection\CopySelectionModel;
use RI\FileManagerBundle\Tests\BaseTestCase;

class CopySelectionModelTest extends BaseTestCase
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
     * @var CopySelectionModel
     */
    private $CopySelectionModel;

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

    /**
     * @var EntityManager
     */
    private $entityManagerMock;

    /**
     * @var UploadDirectoryManager
     */
    private $uploadDirectoryManagerMock;

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
        $this->entityManagerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
        $this->uploadDirectoryManagerMock = $this->getMockBuilder('RI\FileManagerBundle\Manager\UploadDirectoryManager')
            ->disableOriginalConstructor()
            ->getMock();


        $this->CopySelectionModel = new CopySelectionModel($this->fileRepositoryMock, $this->directoryRepositoryMock, $this->directoryDataProviderMock, $this->entityManagerMock, $this->uploadDirectoryManagerMock);
    }

    /**
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::move
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::getDirectoryById
     *
     * @expectedException \RI\FileManagerBundle\Exceptions\CopyMoveSelectionException
     */
    public function testCopy_ShouldReturnException_IfNoDestinationDirAndDirIdNonZero()
    {
        $this->directoryRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(-1)
            ->will($this->returnValue(null));

        $this->CopySelectionModel->copy(-1, array(self::FILE_ID_1), array());
    }

    /**
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::move
     */
    public function testCopy_ShouldReturnFalse_IfNoFilesAndDirectories()
    {
        $this->assertFalse($this->CopySelectionModel->copy(self::DIR_ID_1, array(), array()));
    }

    /**
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::move
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::getDirectoryById
     *
     * @expectedException \RI\FileManagerBundle\Exceptions\CopyMoveSelectionException
     */
    public function testCopy_ShouldThrowException_IfDestinationFolderIsChildOfMovedFolder()
    {
        $this->setMocks();

        $this->entityManagerMock
            ->expects($this->any())
            ->method('persist');

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

        $this->fileRepositoryMock
            ->expects($this->once())
            ->method('fetchFromDir')
            ->with($this->directories[self::DIR_ID_2])
            ->will($this->returnValue($this->files[self::FILE_ID_2]));

        $this->CopySelectionModel->copy(self::DIR_ID_1_1, array(), array(self::DIR_ID_2, self::DIR_ID_1));
    }

    /**
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::move
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::getDirectoryById
     *
     * @expectedException \RI\FileManagerBundle\Exceptions\CopyMoveSelectionException
     */
    public function testCopy_ShouldThrowException_IfDestinationFolderIsInSelection()
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

        $this->CopySelectionModel->copy(self::DIR_ID_1, array(), array(self::DIR_ID_1));
    }

    /**
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::move
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::copyFolders
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::getDirectoryById
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::getFileById
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::copyFiles
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::copyFile
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::copyFileOnDisk
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::copyFolderRecursive
     * @covers RI\FileManagerBundle\Model\CopySelectionModel::copyFolders
     */
    public function testCopy_ShouldReturnTrue()
    {
        $this->setMocks();


        $this->directoryDataProviderMock
            ->expects($this->once())
            ->method('getDirectoryParentsList')
            ->with($this->directories[self::DIR_ID_1])
            ->will($this->returnValue(array()));

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
            ->method('fetchFromDir')
            ->with($this->directories[self::DIR_ID_2])
            ->will($this->returnValue($this->files[self::FILE_ID_2]));

        $this->fileRepositoryMock
            ->expects($this->at(1))
            ->method('find')
            ->with(self::FILE_ID_1)
            ->will($this->returnValue($this->files[self::FILE_ID_1]));
        $this->fileRepositoryMock
            ->expects($this->at(2))
            ->method('find')
            ->with(self::FILE_ID_2)
            ->will($this->returnValue($this->files[self::FILE_ID_2]));

        $this->entityManagerMock->expects($this->any())
            ->method('persist');

        $this->uploadDirectoryManagerMock
            ->expects($this->at(0))
            ->method('getNewPath')
            ->with('abc.jpg')
            ->will($this->returnValue('/../../files/abc_1.jpg'));

        $this->uploadDirectoryManagerMock
            ->expects($this->at(1))
            ->method('getAbsoluteUploadDir')
            ->will($this->returnValue(__DIR__));

        $this->uploadDirectoryManagerMock
            ->expects($this->at(2))
            ->method('getNewPath')
            ->with('xyz.jpg')
            ->will($this->returnValue('/../../files/xyz_1.jpg'));

        $this->uploadDirectoryManagerMock
            ->expects($this->at(3))
            ->method('getAbsoluteUploadDir')
            ->will($this->returnValue(__DIR__));

        $result = $this->CopySelectionModel->copy(self::DIR_ID_1, array(self::FILE_ID_1, self::FILE_ID_2), array(self::DIR_ID_2));

        $this->assertTrue($result);
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
        $file1->setPath('/../../files/abc.jpg');
        $file2 = new File();
        $file2->setDirectory($directory2);
        $file2->setPath('/../../files/xyz.jpg');

        $this->files = array();
        $this->files[self::FILE_ID_1] = $file1;
        $this->files[self::FILE_ID_2] = $file2;
    }


}
 