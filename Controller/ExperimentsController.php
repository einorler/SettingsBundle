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

use DeviceDetector\DeviceDetector;
use DeviceDetector\Parser\Client\Browser;
use DeviceDetector\Parser\Client\ClientParserAbstract;
use DeviceDetector\Parser\Device\DeviceParserAbstract;
use DeviceDetector\Parser\OperatingSystem;
use DeviceDetector\Parser\ParserAbstract;
use ONGR\SettingsBundle\Document\Experiment;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SettingsListController. Is used for managing settings in General env.
 */
class ExperimentsController extends Controller
{
    public function listAction(Request $request)
    {
        $dd = new DeviceDetector($request->headers->get('User-Agent'));
        $dd->parse();

        if ($dd->isBot()) {
            return new JsonResponse('Bots be gon!');
        }

        $json = json_encode(
            [
                'clientInfo' => $dd->getClient(),
                'osInfo' => $dd->getOs(),
                'device' => $dd->getDevice(),
                'brand' => $dd->getBrand(),
                'model' => $dd->getModel(),
            ],
            JSON_PRETTY_PRINT
        );

//        return new JsonResponse($json, 200, [], true);
        return $this->render('ONGRSettingsBundle:Experiments:list.html.twig', [

        ]);
    }

    /**
     * Returns a json list of experiments
     *
     * @return JsonResponse
     */
    public function getFullExperimentsAction()
    {
        $experiments = $this->get('ongr_settings.experiments_manager')->getAllExperimentsArray();

        return new JsonResponse(
            ['count' => 1, 'documents' => $experiments]
        );
    }

    /**
     * Returns a json list of targets for experiment
     *
     * @return JsonResponse
     */
    public function getTargetsAction()
    {
        $targets = [];
        $targets['Devices'] = [
            'desktop',
            'smartphone',
            'tablet',
            'car browser',
            'console',
            'tv',
        ];
        $targets['Clients'] = [
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
        ];
        $targets['OS'] = [
            'Mac',
            'Windows',
            'iOS',
            'Android',
            'Ubuntu',
            'Debian',
        ];

        return new JsonResponse($targets);
    }

    /**
     * Submit action to create or edit setting if not exists.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function submitAction(Request $request)
    {
        try {
            $manager = $this->get('ongr_settings.experiments_manager');
            $data = $request->get('experiment');

            if ($request->get('force')) {
                $name = $request->get('name');
                $manager->update($name, $data);
            } else {
                $manager->create($data);
            }

            return new JsonResponse(['error' => false]);
        } catch (\Exception $e) {
            return new JsonResponse(
                [
                    'error' => true,
                    'message' => 'Error occurred! Something is wrong with provided data. '.
                        'Please try to submit the form again.'
                ]
            );
        }
    }
}
