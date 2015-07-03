<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use RI\FileManagerBundle\Entity\File;
use RI\FileManagerBundle\Model\UploadedFileParametersModel;

class LoadFilesData extends AbstractFixture implements OrderedFixtureInterface
{
    private $data = array(
        array(
            'name' => 'File 1',
            'path' => '/some/path',
            'directory_reference' => 'directory1',
            'file_reference' => 'file1'
        ),
        array(
            'name' => 'File 2',
            'path' => '/some/path2',
            'directory_reference' => 'directory2',
            'file_reference' => 'file2'
        )
    );

    public function load(ObjectManager $manager)
    {
        foreach($this->data as $fileData) {
            $file = new File();
            $file->setDirectory($this->getReference($fileData['directory_reference']));
            $file->setName($fileData['name']);
            $file->setParams(new UploadedFileParametersModel());
            $file->setPath($fileData['path']);
            $manager->persist($file);

            $this->addReference($fileData['file_reference'], $file);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 102;
    }
} 