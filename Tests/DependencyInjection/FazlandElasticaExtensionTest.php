<?php

namespace Fazland\ElasticaBundle\Tests\DependencyInjection;

use Fazland\ElasticaBundle\DependencyInjection\FazlandElasticaExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Yaml\Yaml;

class FazlandElasticaExtensionTest extends \PHPUnit_Framework_TestCase
{
    public function testShouldAddParentParamToObjectPersisterCall()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/fixtures/config.yml'));

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setParameter('kernel.debug', true);

        $extension = new FazlandElasticaExtension();

        $extension->load($config, $containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition('fazland_elastica.object_persister.test_index.child_field'));

        $persisterCallDefinition = $containerBuilder->getDefinition('fazland_elastica.object_persister.test_index.child_field');

        $arguments = $persisterCallDefinition->getArguments();
        $arguments = $arguments['index_3'];

        $this->assertArrayHasKey('_parent', $arguments);
        $this->assertEquals('parent_field', $arguments['_parent']['type']);
    }

    public function testExtensionSupportsDriverlessTypePersistence()
    {
        $config = Yaml::parse(file_get_contents(__DIR__.'/fixtures/driverless_type.yml'));

        $containerBuilder = new ContainerBuilder();
        $containerBuilder->setParameter('kernel.debug', true);

        $extension = new FazlandElasticaExtension();
        $extension->load($config, $containerBuilder);

        $this->assertTrue($containerBuilder->hasDefinition('fazland_elastica.index.test_index'));
        $this->assertTrue($containerBuilder->hasDefinition('fazland_elastica.index.test_index.driverless'));
        $this->assertFalse($containerBuilder->hasDefinition('fazland_elastica.elastica_to_model_transformer.test_index.driverless'));
        $this->assertFalse($containerBuilder->hasDefinition('fazland_elastica.object_persister.test_index.driverless'));
    }
}
