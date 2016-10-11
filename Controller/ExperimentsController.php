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
        $manager = $this->get('ongr_settings.experiments_manager');

        return new JsonResponse(
            ['count' => 1, 'documents' => [['active' => true, 'name' => 'test', 'profiles' => 'foo, bar']]]
        );
    }
}
