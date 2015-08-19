<?php
/*
 * This file is part of the RIFilemanagerBundle package.
 *
 * (c) Rafal Ignaszewski <https://github.com/qjon>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace RI\FileManagerBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 *
 * @codeCoverageIgnore
 */
class RIFileManagerExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('ri.filemanager.upload_dir', $config['upload_dir']);
        $container->setParameter('ri.filemanager.resize', $config['resize']);
        $container->setParameter('ri.filemanager.resize_max_width', $config['resize_max_width']);
        $container->setParameter('ri.filemanager.dimensions', $config['dimensions']);
        $container->setParameter('ri.filemanager.js_config', $this->getJsConfig($config));

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }


    private function getJsConfig(array $config)
    {
        return array(
            'availableDimensions' => $config['dimensions'],
            'allowChangeLanguage' => $config['allow_change_language'],
            'defaultLanguage' => $config['default_language']
        );
    }
}
