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
use RI\FileManagerBundle\DataProvider\DirectoryDataProvider;
use RI\FileManagerBundle\Tests\BaseTestCase;

class DirectoryDataProviderTest extends BaseTestCase
{
    /**
     * @var EntityManager
     */
    private $entityManagerMock;

    /**
     * @var DirectoryDataProvider
     */
    private $directoryDataProvider;

    public function setUp()
    {
        $this->entityManagerMock = $this->createMock('Doctrine\ORM\EntityManager');
        $this->directoryDataProvider = new DirectoryDataProvider($this->entityManagerMock);
    }


    /**
     * @covers RI\FileManagerBundle\DataProvider\DirectoryDataProvider::getRootSubDirectories
     */
    public function testGetRootSubDirectories()
    {
        $directoryId = null;
        $date1 = \DateTime::createFromFormat("Y-m-d H:i:s", '2012-01-01 23:13:11');
        $date2 = \DateTime::createFromFormat("Y-m-d H:i:s", '2015-10-01 12:13:11');

        $directories = array(
            $this->prepareDirectoryMock(7, 'Directory 1', $directoryId, $date1),
            $this->prepareDirectoryMock(10, 'Directory 2', $directoryId, $date2),
        );

        $expectedData = array(
            array(
                'id' => 7,
                'name' => 'Directory 1',
                'parent_id' => 0,
                'createAt' => $date1
            ),
            array(
                'id' => 10,
                'name' => 'Directory 2',
                'parent_id' => 0,
                'createAt' => $date2
            )
        );

        $repositoryMock = $this->createMock('RI\FileManagerBundle\Entity\DirectoryRepository');

        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(array('parent' => $directoryId))
            ->will($this->returnValue($directories));

        $this->entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with('RIFileManagerBundle:Directory')
            ->will($this->returnValue($repositoryMock));

        $this->assertEquals($expectedData, $this->directoryDataProvider->getRootSubDirectories());
    }


    /**
     * @covers RI\FileManagerBundle\DataProvider\DirectoryDataProvider::getDirectorySubDirectories
     */
    public function testGetDirectorySubDirectories()
    {
        $directoryId = 7;
        $date1 = \DateTime::createFromFormat("Y-m-d H:i:s", '2012-01-01 23:13:11');
        $date2 = \DateTime::createFromFormat("Y-m-d H:i:s", '2015-10-01 12:13:11');

        $directories = array(
            $this->prepareDirectoryMock(7, 'Directory 1', $directoryId, $date1),
            $this->prepareDirectoryMock(10, 'Directory 2', $directoryId, $date2),
        );

        $expectedData = array(
            array(
                'id' => 7,
                'name' => 'Directory 1',
                'parent_id' => $directoryId,
                'createAt' => $date1
            ),
            array(
                'id' => 10,
                'name' => 'Directory 2',
                'parent_id' => $directoryId,
                'createAt' => $date2
            )
        );

        $repositoryMock = $this->createMock('RI\FileManagerBundle\Entity\DirectoryRepository');

        $repositoryMock->expects($this->once())
            ->method('findBy')
            ->with(array('parent' => $directoryId))
            ->will($this->returnValue($directories));

        $this->entityManagerMock->expects($this->once())
            ->method('getRepository')
            ->with('RIFileManagerBundle:Directory')
            ->will($this->returnValue($repositoryMock));

        $this->assertEquals($expectedData, $this->directoryDataProvider->getDirectorySubDirectories($directoryId));
    }


    /**
     * @covers RI\FileManagerBundle\DataProvider\DirectoryDataProvider::convertDirectoryEntityToArray
     * @dataProvider dataProviderDirectories
     */
    public function testConvertDirectoryEntityToArray($id, $name, $parentId, $dateTime)
    {
        $dateTime = \DateTime::createFromFormat("Y-m-d H:i:s", $dateTime);
        $directoryMock = $this->prepareDirectoryMock($id, $name, $parentId, $dateTime);

        $expectedArray = array(
            'id' => $id,
            'parent_id' => $parentId,
            'name' => $name,
            'createAt' => $dateTime
        );

        $this->assertEquals($expectedArray, $this->directoryDataProvider->convertDirectoryEntityToArray($directoryMock));
    }


    /**
     * @return array
     */
    public function dataProviderDirectories()
    {
        return array(
            array(7, 'Directory 1', 1, '2012-01-01 12:13:23'),
            array(10, 'Directory 2', null, '2015-08-21 14:03:23')
        );
    }


    /**
     * @covers RI\FileManagerBundle\DataProvider\DirectoryDataProvider::getDirectoryParentsList
     */
    public function testGetDirectoryParentList_ShouldReturnEmptyArray()
    {
        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');

        $this->assertEquals(array(), $this->directoryDataProvider->getDirectoryParentsList($directoryMock, true));
    }


    /**
     * @covers RI\FileManagerBundle\DataProvider\DirectoryDataProvider::getDirectoryParentsList
     */
    public function testGetDirectoryParentList_ShouldReturnConvertedArray()
    {
        $rootDirCreateDate = \DateTime::createFromFormat("Y-m-d H:i:s", '2015-10-01 12:13:11');

        $rootDirMock = $this->prepareDirectoryMock(1, 'Root dir', null, $rootDirCreateDate);
        $levelOneDirMock = $this->prepareDirectoryMock(2, 'level 1 dir', 1, $rootDirCreateDate, $rootDirMock);

        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');
        $directoryMock->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue($levelOneDirMock));

        $expectedData = array(
            array(
                'id' => 1,
                'name' => 'Root dir',
                'parent_id' => 0,
                'createAt' => $rootDirCreateDate
            ),
            array(
                'id' => 2,
                'name' => 'level 1 dir',
                'parent_id' => 1,
                'createAt' => $rootDirCreateDate
            )
        );

        $this->assertEquals($expectedData, $this->directoryDataProvider->getDirectoryParentsList($directoryMock, true));
    }

    /**
     * @covers RI\FileManagerBundle\DataProvider\DirectoryDataProvider::getDirectoryParentsList
     */
    public function testGetDirectoryParentList_ShouldReturnNotConvertedArray()
    {
        $rootDirMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');
        $rootDirMock->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue(null));

        $levelOneDirMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');
        $levelOneDirMock->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue($rootDirMock));

        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');
        $directoryMock->expects($this->once())
            ->method('getParent')
            ->will($this->returnValue($levelOneDirMock));

        $expectedData = array(
            $rootDirMock,
            $levelOneDirMock
        );

        $this->assertEquals($expectedData, $this->directoryDataProvider->getDirectoryParentsList($directoryMock, false));
    }



    private function prepareDirectoryMock($id, $name, $parentId, \DateTime $createAt, $parentMock = null)
    {
        if ($parentMock)
        {
            $parentDirectoryMock = $parentMock;
            $parentDirectoryMock->expects($this->any())
                ->method('getId')
                ->will($this->returnValue($parentId));

        }
        else if ($parentId)
        {
            $parentDirectoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');

            $parentDirectoryMock->expects($this->any())
                ->method('getId')
                ->will($this->returnValue($parentId));

        }
        else
        {
            $parentDirectoryMock = null;
        }
        $directoryMock = $this->createMock('RI\FileManagerBundle\Entity\Directory');

        $directoryMock->expects($this->any())
            ->method('getParent')
            ->will($this->returnValue($parentDirectoryMock));

        $directoryMock->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));

        $directoryMock->expects($this->once())
            ->method('getName')
            ->will($this->returnValue($name));

        $directoryMock->expects($this->once())
            ->method('getCreateAt')
            ->will($this->returnValue($createAt));

        return $directoryMock;
    }
}
