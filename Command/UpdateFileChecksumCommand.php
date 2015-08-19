<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Command;

use RI\FileManagerBundle\Entity\File;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateFileChecksumCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this->setName('rifilemanager:checksum:update')->setDescription('Update file checksum value');
    }


    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            $files = $this->getContainer()->get('ri.filemanager.repository.file')->fetchFilesWithoutChecksum();
            $fileModel = $this->getContainer()->get('ri.filemanager.model.file_model');

            /** @var File $file */
            foreach($files as $file)
            {
                $file->setChecksum($fileModel->getChecksum($file->getPath()));
            }

            $this->getContainer()->get('doctrine.orm.default_entity_manager')->flush();
        } catch (\Exception $exception) {
            $output->writeln(sprintf('<error>\s</error>', $exception->getMessage()));
        }

    }
}