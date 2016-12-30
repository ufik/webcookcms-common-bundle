<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook
 */

namespace Webcook\Cms\CommonBundle\ContentProvider;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Webcook\Cms\CoreBundle\Entity\Page;
use Webcook\Cms\CoreBundle\Entity\Section;
use Webcook\Cms\CoreBundle\ContentProvider\AbstractContentProvider;

class TextContentProvider extends AbstractContentProvider
{
    /** @inheritDoc */
    public function getContent(Page $page, Section $section): string
    {
        $settings = $this->em->getRepository('\Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->findOneBy(array(
            'page'    => $page,
            'section' => $section
        ));

        if (is_null($settings)) {
            $text = '';
        } else {
            $text = $settings->getText();
        }

        return $this->twig->render(
            'WebcookCmsCommonBundle:ContentProvider:text.html.twig',
            array(
                'text' => $text
            )
        );
    }
}
