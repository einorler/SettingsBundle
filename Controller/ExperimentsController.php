<?php

/*
 * This file is part of the ONGR package.
 *
 * (c) NFQ Technologies UAB <info@nfq.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace ONGR\SettingsBundle\Controller;

use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\Client\ClientParserAbstract;
use DeviceDetector\Parser\Client\Library;
use DeviceDetector\Parser\Client\MobileApp;
use DeviceDetector\Parser\Client\MediaPlayer;
use DeviceDetector\Parser\Client\PIM;
use DeviceDetector\Parser\Client\FeedReader;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use DeviceDetector\Parser\OperatingSystem;
use ONGR\SettingsBundle\Document\Setting;
use ONGR\SettingsBundle\Service\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SettingsListController. Is used for managing settings in General env.
 */
class ExperimentsController extends Controller
{
    public function listAction(Request $request)
    {
        return $this->render('ONGRSettingsBundle:Experiments:list.html.twig');
    }

    /**
     * Returns a json list of experiments
     *
     * @return JsonResponse
     */
    public function getFullExperimentsAction()
    {
        $experimentsArray = [];
        /** @var SettingsManager $manager */
        $manager = $this->get('ongr_settings.settings_manager');
        $experiments = $manager->getAllExperiments();
        $activeExperiments = $manager->getActiveExperiments();

        /** @var Setting $experiment */
        foreach ($experiments as $experiment) {
            $experiment = $experiment->getSerializableData();
            $experiment['active'] = $activeExperiments;
            $experimentsArray[] = $experiment;
        }

        return new JsonResponse(
            ['count' => count($experiments), 'documents' => $experimentsArray]
        );
    }

    /**
     * Returns a json list of targets for experiment
     *
     * @return JsonResponse
     */
    public function getTargetsAction()
    {
        $targets = [
            'Devices' => [
                'types' => DeviceParserAbstract::getAvailableDeviceTypeNames(),
                'brands' => DeviceParserAbstract::$deviceBrands,
            ],
            'Clients' => [
                'types' => [
                    'Browser',
                    'FeedReader',
                    'Library',
                    'MediaPlayer',
                    'MobileApp',
                    'PIM',
                ]
            ],
            'OS' => OperatingSystem::getAvailableOperatingSystems(),
        ];

        return new JsonResponse($targets);
    }

    /**
     * Returns a json list of targets for experiment
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getTargetsAttributesAction(Request $request)
    {
        $types = $request->get('types');

        $clients = [];

        try {
            foreach ($types as $type) {
                $clients = array_merge(
                    ("\\DeviceDetector\\Parser\\Client\\$type")::getAvailableClients(),
                    $clients
                );
            }
        } catch (\Exception $e) {
            return new JsonResponse(['error' => true]);
        }

        return new JsonResponse($clients);
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function toggleAction(Request $request)
    {
        $name = $request->get('name');
        try {
            $this->get('ongr_settings.settings_manager')->toggleExperiment($name);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => true]);
        }

        return new JsonResponse(['error' => false]);
    }
}
