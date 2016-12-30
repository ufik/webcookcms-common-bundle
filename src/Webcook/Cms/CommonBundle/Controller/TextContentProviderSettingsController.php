<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook 
 */

namespace Webcook\Cms\CommonBundle\Controller;

use Webcook\Cms\CoreBundle\Base\BaseRestController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings;
use Webcook\Cms\CommonBundle\Form\Type\TextContentProviderSettingsType;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Webcook\Cms\SecurityBundle\Authorization\Voter\WebcookCmsVoter;
use FOS\RestBundle\Controller\Annotations\Get;
use FOS\RestBundle\Controller\Annotations\Post;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;
use Doctrine\DBAL\LockMode;

/**
 * Page controller.
 */
class TextContentProviderSettingsController extends BaseRestController
{
    /**
     * Get single settings for text content provider.
     *
     * @param int $id Id of the desired settings.
     *
     * @ApiDoc(
     *  description="Return single settings.",
     *  parameters={
     *      {"name"="PageId", "dataType"="integer", "required"=true, "description"="Page id."},
     *      {"name"="SectionId", "dataType"="integer", "required"=true, "description"="Section id."}
     *  }
     * )
     * @Get("/content-providers/text/settings/{pageId}/{sectionId}", options={"i18n"=false})
     */
    public function getTextContentProviderSettingsAction($pageId, $sectionId)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_VIEW);

        $page     = $this->getEntityManager()->getRepository('Webcook\Cms\CommonBundle\Entity\Page')->find($pageId);
        $section  = $this->getEntityManager()->getRepository('Webcook\Cms\CommonBundle\Entity\Section')->find($sectionId);

        $settings = $this->getEntityManager()->getRepository('Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->findOneBy(array(
            'page'    => $page,
            'section' => $section
        ));

        if (is_null($settings)) {
            $view = $this->getViewWithMessage(null, 400, 'Settings not found.');
        } else {
            $view = $this->view($settings, 200);
        }

        return $this->handleView($view);
    }

    /**
     * Save new settings.
     *
     * @ApiDoc(
     *  description="Create a new text content provider settings.",
     *  input="Webcook\Cms\CommonBundle\Form\Type\MenuContentProviderSettingsType",
     *  output="Webcook\Cms\CommonBundle\Entity\MenuContentProviderSettings",
     * )
     * @Post("/content-providers/text/settings", options={"i18n"=false})
     */
    public function postTextContentProviderSettingsAction()
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_INSERT);

        $response = $this->processSettingsForm(new TextContentProviderSettings(), 'POST');

        if ($response instanceof TextContentProviderSettings) {
            $statusCode = 200;
            $message = 'Settings has been added.';
        } else {
            $statusCode = 400;
            $message = 'Error while adding new settings.';
        }

        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * Update settings.
     *
     * @param int $id Id of the desired settings.
     *
     * @ApiDoc(
     *  description="Update existing settings.",
     *  input="Webcook\Cms\CommonBundle\Form\Type\TextContentProviderSettingsType",
     *  output="Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings"
     * )
     * @Put("/content-providers/text/settings/{id}", options={"i18n"=false})
     */
    public function putTextContentProviderSettingsAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_EDIT);

        try {
            $settings = $this->getSettingsById($id, $this->getLockVersion((string) new TextContentProviderSettings()));
        } catch (NotFoundHttpException $e) {
            $settings = new TextContentProviderSettings();
        }

        $response = $this->processSettingsForm($settings, 'PUT');

        if ($response instanceof TextContentProviderSettings) {
            $statusCode = 204;
            $message = 'Settings has been updated.';
        } else {
            $statusCode = 400;
            $message = 'Error while updating settings.';
        }

        $view = $this->getViewWithMessage($response, $statusCode, $message);

        return $this->handleView($view);
    }

    /**
     * Delete settings.
     *
     * @param int $id Id of the desired settings.
     *
     * @ApiDoc(
     *  description="Delete settings.",
     *  parameters={
     *     {"name"="SettingId", "dataType"="integer", "required"=true, "description"="Page id."}
     *  }
     * )
     * @Delete("/content-providers/text/settings/{id}", options={"i18n"=false})
     */
    public function TextContentProviderSettingsAction($id)
    {
        $this->checkPermission(WebcookCmsVoter::ACTION_DELETE);

        $settings = $this->getSettingsById($id);

        $this->getEntityManager()->remove($settings);
        $this->getEntityManager()->flush();

        $view = $this->getViewWithMessage(array(), 200, 'Settings has been deleted.');

        return $this->handleView($view);
    }

    /**
     * Return form if is not valid, otherwise process form and return Page object.
     *
     * @param Page   $settings
     * @param string $method Method of request
     *
     * @return \Symfony\Component\Form\Form [description]
     */
    private function processSettingsForm(TextContentProviderSettings $settings, String $method = 'POST')
    {
        $form = $this->createForm(TextContentProviderSettingsType::class, $settings);
        $form = $this->formSubmit($form, $method);
        if ($form->isValid()) {
            $settings = $form->getData();

            if ($settings instanceof TextContentProviderSettings) {
                $this->getEntityManager()->persist($settings);
            }

            $this->getEntityManager()->flush();

            return $settings;
        }

        return $form;
    }

    /**
     * Get Settings by id.
     *
     * @param int $id Identificator.
     *
     * @return MenuContentProviderSettings
     *
     * @throws NotFoundHttpException If settings object doesn't exist.
     */
    private function getSettingsById(int $id, int $expectedVersion = null)
    {
        if ($expectedVersion) {
            $settings = $this->getEntityManager()->getRepository('Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->find($id, LockMode::OPTIMISTIC, $expectedVersion);
        } else {
            $settings = $this->getEntityManager()->getRepository('Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->find($id);
        }

        if (!$settings instanceof TextContentProviderSettings) {
            throw new NotFoundHttpException('Settings not found.');
        }

        $this->saveLockVersion($settings);

        return $settings;
    }
}
