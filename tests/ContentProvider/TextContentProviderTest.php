<?php

namespace Webcook\Cms\CommonBundle\ContentProvider;

use Webcook\Cms\CommonBundle\ContentProvider\TextContentProvider;

class TextContentProviderTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testTextContentProviderContent()
    {
        $this->loadData();

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find(7);
        $section = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Section')->find(2);

        $textContentProvider = $this->container->get('webcookcms.common.text_content_provider');
        $content = $textContentProvider->getContent($page, $section);
        
        $this->assertContains('<p>Test text</p>', $content);
    }

    private function loadData()
    {
        $this->loadFixtures(array(
            'Webcook\Cms\CommonBundle\DataFixtures\ORM\LoadContentProviderData',
            'Webcook\Cms\CommonBundle\DataFixtures\ORM\LoadTextContentProviderSettingsData'
        ));
    }
}
