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

use ONGR\SettingsBundle\Settings\General\SettingsManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Exception\ParameterNotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Yaml\Parser;

/**
 * SettingsManager controller responsible for CRUD actions from frontend for settings.
 *
 * @package ONGR\SettingsBundle\Controller
 */
class SettingsManagerController extends Controller
{
    /**
     * Action for saving/setting setting values.
     *
     * @param Request $request
     *
     * @return Response
     */
    public function setSettingAction(Request $request)
    {
        $data = json_decode($request->request->get('data'), true);
        $manager = $this->getSettingsManager();
        $parser = new Parser();
        $value = $data['setting_value'];
        $type = $data['setting_type'];
        $name = htmlentities($data['setting_name']);
        $profiles = $data['setting_profiles'];
        $description = htmlentities($data['setting_description']);
        $response = [];
        $response['error'] = '';

        switch ($type) {
            case 'bool':
                $value == 'true' ? $value = true : $value = false;
                break;
            case 'default':
                $value = htmlentities($value);
                break;
            case 'object':
                try {
                    $value = json_encode($parser->parse($value));
                } catch (\Exception $e) {
                    $response['error'] = 'Passed setting value does not contain valid yaml';
                    return new JsonResponse(json_encode($response));
                }
                break;
            case 'array':
                foreach ($value as $key => $item) {
                    $value[$key] = htmlentities($item);
                }
                break;
        }
        try {
            foreach ($profiles as $profile) {
                $manager->set($name, $description, $type, $value, $profile);
            }
        } catch (\Exception $e) {
            $response['error'] = 'Insertion failed: '.$e->getMessage();
        }
        return new JsonResponse(json_encode($response));
    }

    /**
     * Action for rendering single setting edit page.
     *
     * @param Request $request
     * @param string  $name
     * @param string  $profile
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function editAction(Request $request, $name, $profile)
    {
        $setting = $this->getSettingsManager()->get($name, $profile, false, $request->query->get('type', 'string'));

        return $this->render(
            'ONGRSettingsBundle:Settings:edit.html.twig',
            [
                'setting' => $setting,
            ]
        );
    }

    /**
     * Action for Angularjs to edit settings.
     *
     * @param Request $request
     * @param string  $name
     * @param string  $profile
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function ngEditAction(Request $request, $name, $profile)
    {
        $content = $request->getContent();
        if (empty($content)) {
            return new Response(Response::$statusTexts[400], 400);
        }

        $content = json_decode($content, true);
        if ($content === null || empty($content['setting'])) {
            return new Response(Response::$statusTexts[400], 400);
        }

        $type = isset($content['setting']['type']) ? $content['setting']['type'] : 'string';

        $manager = $this->getSettingsManager();
        $model = $manager->get($name, $profile, false, $type);

        $model->setData($content['setting']['data']);

        if (isset($content['setting']['description'])) {
            $model->setDescription($content['setting']['description']);
        }

        $manager->save($model);

        return new Response();
    }

    /**
     * Action for deleting a setting.
     *
     * @param string $name
     * @param string $profile
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function removeAction($name, $profile)
    {
        $setting = $this->getSettingsManager()->get($name, $profile);

        $this->getSettingsManager()->remove($setting);

        return new Response();
    }

    /**
     * Copies a setting to a new profile.
     *
     * @param string $name
     * @param string $from
     * @param string $to
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function copyAction($name, $from, $to)
    {
        $settingsManager = $this->getSettingsManager();

        $setting = $settingsManager->get($name, $from);

        $this->getSettingsManager()->duplicate($setting, $to);

        return new Response();
    }

    /**
     * @return SettingsManager
     */
    protected function getSettingsManager()
    {
        return $this->get('ongr_settings.settings_manager');
    }
}
