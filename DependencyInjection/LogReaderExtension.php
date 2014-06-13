<?php

namespace Symfony\Bundle\LogReaderBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class LogReaderExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if(!isset($config['log_file'])) {
            throw new \InvalidArgumentException(
                'The log_file must be set.'
            );
        }

        $container->setParameter('log_folder', $config['log_folder']);
        $container->setParameter('default_file', $config['default_file']);
        $container->setParameter('log_file', $config['log_file']);
        $container->setParameter('log_file_path', $config['log_folder'].$config['log_file']);
   }
    
}
