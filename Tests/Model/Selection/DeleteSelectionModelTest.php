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

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use RI\FileManagerBundle\Entity\Directory;
use RI\FileManagerBundle\Entity\DirectoryRepository;
use RI\FileManagerBundle\Entity\File;
use RI\FileManagerBundle\Entity\FileRepository;
use RI\FileManagerBundle\Model\Selection\DeleteSelectionModel;
use RI\FileManagerBundle\Tests\BaseTestCase;

class DeleteSelectionModelTest extends BaseTestCase
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
     * @var DeleteSelectionModel
     */
    private $deleteSelectionModel;

    /**
     * @var FileRepository
     */
    private $fileRepositoryMock;

    /**
     * @var DirectoryRepository
     */
    private $directoryRepositoryMock;

    /**
     * @var EntityManager
     */
    private $entityManagerMock;

    public function setUp()
    {
        $this->fileRepositoryMock = $this->getMockBuilder('RI\FileManagerBundle\Entity\FileRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->directoryRepositoryMock = $this->getMockBuilder('RI\FileManagerBundle\Entity\DirectoryRepository')
            ->disableOriginalConstructor()
            ->getMock();
        $this->entityManagerMock = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();


        copy(__DIR__ . '/../../files/org_abc.jpg', __DIR__ . '/../../files/abc.jpg');
        copy(__DIR__ . '/../../files/org_xyz.jpg', __DIR__ . '/../../files/xyz.jpg');

        $this->deleteSelectionModel = new DeleteSelectionModel($this->fileRepositoryMock, $this->directoryRepositoryMock, $this->entityManagerMock);
    }

    /**
     * @covers RI\FileManagerBundle\Model\DeleteSelectionModel::delete
     */
    public function testDelete_ShouldReturnFalse_IfNoFilesAndDirectories()
    {
        $this->assertFalse($this->deleteSelectionModel->delete(array(), array()));
    }

    /**
     * @covers RI\FileManagerBundle\Model\DeleteSelectionModel::delete
     * @expectedException \RI\FileManagerBundle\Exceptions\RemoveDirException
     */
    public function testDelete_ShouldThrowException_IfFolderHasSomeFiles()
    {
        $this->setMocks();

        $this->directoryRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::DIR_ID_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1]));

        $this->fileRepositoryMock
            ->expects($this->once())
            ->method('fetchFromDir')
            ->with($this->directories[self::DIR_ID_1])
            ->will($this->returnValue(array($this->files[self::FILE_ID_1])));

        $this->deleteSelectionModel->delete(array(), array(self::DIR_ID_1));
    }

    /**
     * @covers RI\FileManagerBundle\Model\DeleteSelectionModel::delete
     * @expectedException \RI\FileManagerBundle\Exceptions\RemoveDirException
     */
    public function testDelete_ShouldThrowException_IfFolderHasSubfolders()
    {
        $this->setMocks();

        $this->directoryRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::DIR_ID_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1]));

        $this->fileRepositoryMock
            ->expects($this->once())
            ->method('fetchFromDir')
            ->with($this->directories[self::DIR_ID_1])
            ->will($this->returnValue(new ArrayCollection()));

        $this->deleteSelectionModel->delete(array(), array(self::DIR_ID_1));
    }

    /**
     * @covers RI\FileManagerBundle\Model\DeleteSelectionModel::delete
     */
    public function testDelete_ShouldReturnTrue()
    {
        $this->setMocks();

        $this->fileRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::FILE_ID_1)
            ->will($this->returnValue($this->files[self::FILE_ID_1]));

        $this->directoryRepositoryMock
            ->expects($this->once())
            ->method('find')
            ->with(self::DIR_ID_1_1)
            ->will($this->returnValue($this->directories[self::DIR_ID_1_1]));

        $this->fileRepositoryMock
            ->expects($this->once())
            ->method('fetchFromDir')
            ->with($this->directories[self::DIR_ID_1_1])
            ->will($this->returnValue(array()));

        $this->entityManagerMock
            ->expects($this->exactly(2))
            ->method('remove');

        $this->assertTrue($this->deleteSelectionModel->delete(array(self::FILE_ID_1), array(self::DIR_ID_1_1)));
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
 