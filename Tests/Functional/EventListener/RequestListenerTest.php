<?php

namespace ONGR\SettingsBundle\Tests\Functional;

use ONGR\ElasticsearchBundle\Test\AbstractElasticsearchTestCase;
use ONGR\SettingsBundle\Service\SettingsManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class RequestListener extends AbstractElasticsearchTestCase
{
    /**
     * {@inheritdoc}
     */
    protected function getDataArray()
    {
        return [
            'settings' => [
                'setting' => [
                    [
                        'name' => 'foo',
                        'profile' => ['default'],
                        'type' => 'bool',
                        'value' => true,
                    ],
                ],
            ],
        ];
    }


    /**
     * Data provider for testSetProfiles()
     *
     * @return array
     */
    public function getSetProfilesData()
    {
        $out = [];

        // Case #0 - set profiles to manager on master request
        $out[] = ['requestType' => 1, 'expected' => ['foo', 'bar']];

        // Case #1 - dont set profiles to manager on non master request
        $out[] = ['requestType' => 2, 'expected' => []];

        return $out;
    }

    /**
     * Check if find by works as expected.
     *
     * @param int   $requestType
     * @param array $expected
     *
     * @dataProvider getSetProfilesData()
     */
    public function testSetProfiles($requestType, $expected)
    {
        /** @var SettingsManager $manager */
        $manager  = $this->getContainer()->get('ongr_settings.settings_manager');
        $cookie   = $this->getContainer()->get('ongr_settings.cookie.active_profiles');
        $listener = $this->getContainer()->get('ongr_settings.request_listener');
        $activeProfiles = ['foo', 'bar'];

        $this->assertEmpty($manager->getActiveProfiles());

        $cookie->load(json_encode($activeProfiles));
        $listener->onKernelRequest(
            new GetResponseEvent(static::$kernel, new Request(), $requestType)
        );

        $this->assertEquals($expected, $manager->getActiveProfiles());
    }
}
