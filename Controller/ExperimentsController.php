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
                'desktop',
                'smartphone',
                'tablet',
                'car browser',
                'console',
                'tv',
            ],
            'Clients' => [
                'Firefox',
                'Safari',
                'Chrome',
                'Opera',
                'Edge',
                'IE Mobile',
                'Internet Explorer',
                'Mobile Safari',
                'Android Browser',
                'Chrome Mobile',
                'Chrome Mobile iOS',
                'Opera Mobile',
                'UC Browser',
            ],
            'OS' => [
                'Mac',
                'Windows',
                'iOS',
                'Android',
                'Ubuntu',
                'Debian',
            ],
        ];

        return new JsonResponse($targets);
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
