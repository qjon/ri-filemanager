<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\Tests;

/**
 * Class BaseTestCase
 */
class BaseTestCase extends \PHPUnit_Framework_TestCase
{
    protected $_application;

    public function getContainer()
    {
        return $this->_application->getKernel()->getContainer();
    }

    public function setUp()
    {
        $kernel = new \AppKernel("test", true);
        $kernel->boot();
        $this->_application = new \Symfony\Bundle\FrameworkBundle\Console\Application($kernel);
        $this->_application->setAutoExit(false);
        $this->runConsole("doctrine:schema:drop", array("--force" => true));
        $this->runConsole("doctrine:schema:create");
        $this->runConsole("doctrine:fixtures:load", array("--fixtures" => __DIR__ . "/../DataFixtures", '-n' => ''));
    }

    protected function runConsole($command, Array $options = array())
    {
        $options["--env"] = "test";
        $options = array_merge($options, array('command' => $command));
        return $this->_application->run(new \Symfony\Component\Console\Input\ArrayInput($options));
    }


    /**
     * Helper for creating mock
     * @param $className
     *
     * @return mixed
     */
    protected function createMock($className)
    {
        return $this->getMockBuilder($className)
            ->disableOriginalConstructor()
            ->getMock();
    }


    /**
     * @param string $filename
     *
     * @return string
     */
    protected function prepareTestImage($filename)
    {
        $dir = __DIR__ . '/web/';
        $testFilename = 'test_' . $filename;
        copy($dir . $filename, $dir . $testFilename);

        return $dir . $testFilename;
    }
}
