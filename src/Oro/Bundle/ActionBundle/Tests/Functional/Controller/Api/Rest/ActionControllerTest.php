<?php

namespace Oro\Bundle\ActionBundle\Tests\Functional\Controller\Api\Rest;

use Oro\Bundle\ActionBundle\Configuration\ActionConfigurationProvider;
use Oro\Bundle\ActionBundle\Tests\Functional\DataFixtures\LoadTestEntityData;
use Oro\Bundle\CacheBundle\Provider\FilesystemCache;
use Oro\Bundle\TestFrameworkBundle\Entity\TestActivity;
use Oro\Bundle\TestFrameworkBundle\Test\WebTestCase;

/**
 * @dbIsolation
 */
class ActionControllerTest extends WebTestCase
{
    const MESSAGE_DEFAULT = 'test message';

    const MESSAGE_NEW = 'new test message';

    /** @var TestActivity */
    private $entity;

    /** @var FilesystemCache */
    protected $cacheProvider;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->initClient([], $this->generateWsseAuthHeader());

        $this->cacheProvider = $this->getContainer()->get('oro_action.cache.provider');
        $this->loadFixtures([
            'Oro\Bundle\ActionBundle\Tests\Functional\DataFixtures\LoadTestEntityData',
        ]);

        $this->entity = $this->getReference(LoadTestEntityData::TEST_ENTITY_1)
            ->setMessage(self::MESSAGE_DEFAULT);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        $this->cacheProvider->delete(ActionConfigurationProvider::ROOT_NODE_NAME);
    }

    /**
     * @dataProvider executeActionDataProvider
     *
     * @param array $config
     * @param string $route
     * @param bool $entityId
     * @param string $entityClass
     * @param int $statusCode
     * @param string $message
     */
    public function testExecuteAction(array $config, $route, $entityId, $entityClass, $statusCode, $message)
    {
        $this->cacheProvider->save(ActionConfigurationProvider::ROOT_NODE_NAME, $config);

        $this->assertEquals(self::MESSAGE_DEFAULT, $this->entity->getMessage());

        $this->client->request(
            'GET',
            $this->getUrl(
                'oro_api_action_execute_actions',
                [
                    'actionName' => 'oro_action_test_action',
                    'route' => $route,
                    'entityId' => $entityId ? $this->entity->getId() : null,
                    'entityClass' => $entityClass,
                ]
            )
        );

        $result = $this->client->getResponse();

        $this->assertEquals($message, $this->entity->getMessage());
        $this->assertResponseStatusCodeEquals($result, $statusCode);
    }

    /**
     * @return array
     *
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function executeActionDataProvider()
    {
        $label = 'oro.action.test.label';

        $config = [
            'oro_action_test_action' => [
                'label' => $label,
                'enabled' => true,
                'order' => 10,
                'applications' => ['backend', 'frontend'],
                'frontend_options' => [],
                'entities' => [],
                'routes' => [],
                'functions' => [['@assign_value' => ['$message', 'new test message']]],
            ]
        ];

        return [
            'right conditions' => [
                'config' => array_merge_recursive(
                    $config,
                    [
                        'oro_action_test_action' => [
                            'entities' => ['Oro\Bundle\TestFrameworkBundle\Entity\TestActivity'],
                            'preconditions' => ['@equal' => ['$message', self::MESSAGE_DEFAULT]],
                        ],
                    ]
                ),
                'route' => 'oro_action_test_route',
                'entityId' => true,
                'entityClass' => 'Oro\Bundle\TestFrameworkBundle\Entity\TestActivity',
                'statusCode' => 200,
                'message' => self::MESSAGE_NEW,
            ],
            'wrong conditions' => [
                'config' => array_merge_recursive(
                    $config,
                    [
                        'oro_action_test_action' => [
                            'entities' => ['Oro\Bundle\TestFrameworkBundle\Entity\TestActivity'],
                            'preconditions' => ['@equal' => ['$message', 'test message wrong']],
                        ],
                    ]
                ),
                'route' => 'oro_action_test_route',
                'entityId' => true,
                'entityClass' => 'Oro\Bundle\TestFrameworkBundle\Entity\TestActivity',
                'statusCode' => 404,
                'message' => self::MESSAGE_DEFAULT,
            ],
            'unknown entity' => [
                'config' => array_merge_recursive(
                    $config,
                    ['oro_action_test_action' => ['entities' => ['Oro\Bundle\TestFrameworkBundle\Enti\UnknownEntity']]]
                ),
                'route' => 'oro_action_test_route',
                'entityId' => true,
                'entityClass' => 'Oro\Bundle\TestFrameworkBundle\Entity\TestActivity',
                'statusCode' => 200,
                'message' => self::MESSAGE_DEFAULT,
            ],
            'unknown route' => [
                'config' => array_merge_recursive(
                    $config,
                    ['oro_action_test_action' => ['routes' => ['oro_action_unknown_route']]]
                ),
                'route' => 'oro_action_test_route',
                'entityId' => false,
                'entityClass' => 'Oro\Bundle\TestFrameworkBundle\Entity\TestActivity',
                'statusCode' => 200,
                'message' => self::MESSAGE_DEFAULT,
            ],
            'empty context' => [
                'config' => $config,
                'route' => 'oro_action_test_route',
                'entityId' => true,
                'entityClass' => 'Oro\Bundle\TestFrameworkBundle\Entity\TestActivity',
                'statusCode' => 200,
                'message' => self::MESSAGE_DEFAULT,
            ],
        ];
    }
}
