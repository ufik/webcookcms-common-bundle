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

class TextContentProvider extends AbstractContentProvider
{
    /** @inheritDoc */
    public function getContent(Page $page, Section $section): string
    {
        $settings = $this->em->getRepository('\Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->findOneBy(array(
            'page'    => $page,
            'section' => $section
        ));

        return $this->twig->render(
            'WebcookCmsCommonBundle:ContentProvider:text.html.twig',
            array(
                'text' => $settings->getText()
            )
        );
    }
}
