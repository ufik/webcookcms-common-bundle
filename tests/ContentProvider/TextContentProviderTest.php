<?php

namespace Webcook\Cms\CommonBundle\ContentProvider;

use Webcook\Cms\CommonBundle\ContentProvider\TextContentProvider;

class TextContentProviderTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function testTextContentProviderContent()
    {
        $this->loadData();

        $page = $this->em->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->find(1);
        $section = $page->getSections()[0]->getSection();

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
