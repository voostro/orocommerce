<?php

namespace Oro\Bundle\WebsiteSearchBundle\Tests\Unit\Provider;

use Oro\Component\Config\CumulativeResourceInfo;
use Oro\Component\Config\CumulativeResourceManager;
use Oro\Bundle\WebsiteSearchBundle\Provider\ResourcesBasedMappingConfigurationProvider;
use Oro\Bundle\WebsiteSearchBundle\Tests\Unit\Fixture\Bundle\TestCustomBundle\TestCustomBundle;
use Oro\Bundle\WebsiteSearchBundle\Tests\Unit\Fixture\Bundle\TestPageBundle\TestPageBundle;
use Oro\Bundle\WebsiteSearchBundle\Tests\Unit\Fixture\Bundle\TestProductBundle\TestProductBundle;

class ResourcesBasedMappingConfigurationProviderTest extends \PHPUnit_Framework_TestCase
{
    use ConfigResourcePathTrait;

    /**
     * @var ResourcesBasedMappingConfigurationProvider
     */
    protected $provider;

    protected function setUp()
    {
        $pageBundle = new TestPageBundle();
        $productBundle = new TestProductBundle();
        $customBundle = new TestCustomBundle();

        CumulativeResourceManager::getInstance()
            ->clear()
            ->setBundles([
                $pageBundle->getName() => get_class($pageBundle),
                $productBundle->getName() => get_class($productBundle),
                $customBundle->getName() => get_class($customBundle)
            ]);

        $this->provider = new ResourcesBasedMappingConfigurationProvider();
    }

    public function testGetResources()
    {
        $resourcesPaths = array_map(function (CumulativeResourceInfo $resource) {
            return $resource->path;
        }, $this->provider->getResources());

        $expectedResources = [
            $this->getBundleConfigResourcePath('TestPageBundle', 'website_search.yml'),
            $this->getBundleConfigResourcePath('TestProductBundle', 'website_search.yml'),
            $this->getBundleConfigResourcePath('TestCustomBundle', 'website_search.yml')
        ];

        $this->assertEquals($expectedResources, $resourcesPaths);
    }

    public function testGetConfiguration()
    {
        $expectedConfiguration = [
            'OroB2B\Bundle\TestPageBundle\Entity\Page' => [
                'alias' => 'page_WEBSITE_ID',
                'fields' => [
                    [
                        'name' => 'title_LOCALIZATION_ID',
                        'type' => 'text'
                    ],
                    [
                        'name' => 'custom_field',
                        'type' => 'text'
                    ],
                ]
            ],
            'OroB2B\Bundle\TestProductBundle\Entity\Product' => [
                'alias' => 'product_WEBSITE_ID',
                'fields' => [
                    [
                        'name' => 'title_LOCALIZATION_ID',
                        'type' => 'text'
                    ],
                    [
                        'name' => 'price',
                        'type' => 'decimal'
                    ]
                ]
            ]
        ];

        $this->assertEquals($expectedConfiguration, $this->provider->getConfiguration());
    }
}
