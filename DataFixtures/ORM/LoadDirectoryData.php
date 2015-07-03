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
use RI\FileManagerBundle\Entity\Directory;

class LoadDirectoryData extends AbstractFixture implements OrderedFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $directory1 = new Directory();
        $directory1->setName('Dir one');
        $directory1->setParent(null);
        $manager->persist($directory1);

        $directory2 = new Directory();
        $directory2->setName('Dir second');
        $directory2->setParent(null);
        $manager->persist($directory2);

        $directory3 = new Directory();
        $directory3->setName('subdir of dir one');
        $directory3->setParent($directory1);
        $manager->persist($directory3);

        $this->addReference('directory1', $directory1);
        $this->addReference('directory2', $directory2);
        $this->addReference('directory3', $directory3);


        $manager->flush();
    }

    public function getOrder()
    {
        return 101;
    }
} 