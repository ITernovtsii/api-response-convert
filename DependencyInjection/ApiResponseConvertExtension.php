<?php

namespace tandrewcl\ApiResponseConvertBundle\DependencyInjection;

use Symfony\Component\{
    DependencyInjection\ContainerBuilder, DependencyInjection\Loader\YamlFileLoader, Config\FileLocator,
    HttpKernel\DependencyInjection\Extension
};
use tandrewcl\ApiResponseConvertBundle\Converter\ResponseConverterInterface;

class ApiResponseConvertExtension extends Extension
{
    /**
     * {@inheritdoc}
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container->registerForAutoconfiguration(ResponseConverterInterface::class)
            ->addTag('api_response.converter');

    }
}
