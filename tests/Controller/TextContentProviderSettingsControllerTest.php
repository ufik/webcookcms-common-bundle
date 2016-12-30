<?php

namespace Webcook\Cms\CommonBundle\Tests\Controller;

class TextContentProviderSettingsControllerTest extends \Webcook\Cms\CoreBundle\Tests\BasicTestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->loadData();
    }

    public function testGetSettings()
    {
        $this->createTestClient();
        $this->client->request('GET', '/api/content-providers/text/settings/1/2');

        $settings = $this->client->getResponse()->getContent();

        $data = json_decode($settings, true);
        $this->assertEquals('Main', $data['page']['title']);
        $this->assertEquals('Content', $data['section']['name']);
        $this->assertEquals('<p>Test text</p>', $data['text']);
    }

    public function testGetNonExistingSettings()
    {
        $this->createTestClient();
        $this->client->request('GET', '/api/content-providers/text/settings/1/3');

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPost()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/content-providers/text/settings',
            array(
                'text_content_provider_settings' => array(
                    'page' => 1,
                    'section' => 2,
                    'text' => 'Test <a></a>'
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $settings = $this->em->getRepository('Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->findAll();

        $this->assertCount(2, $settings);
        $this->assertEquals('Main', $settings[1]->getPage()->getTitle());
        $this->assertEquals('Content', $settings[1]->getSection()->getName());
        $this->assertEquals('Test <a></a>', $settings[1]->getText());
    }

    public function testPut()
    {
        $this->createTestClient();

        $this->client->request('GET', '/api/content-providers/text/settings/2'); // save version into session
        $crawler = $this->client->request(
            'PUT',
            '/api/content-providers/text/settings/2',
            array(
                'text_content_provider_settings' => array(
                    'page' => 5,
                    'section' => 2,
                    'text' => 'test'
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $settings = $this->em->getRepository('Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->find(2);

        $this->assertEquals('Footer', $settings->getPage()->getTitle());
        $this->assertEquals('Content', $settings->getSection()->getName());
        $this->assertEquals('test', $settings->getText());
    }

    public function testDelete()
    {
        $this->createTestClient();

        $crawler = $this->client->request('DELETE', '/api/content-providers/text/settings/1');

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $settings = $this->em->getRepository('Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->findAll();

        $this->assertCount(0, $settings);
    }

    public function testWrongPost()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'POST',
            '/api/content-providers/text/settings',
            array(
                'text_content_provider_settings' => array(
                    'n' => 'Tester',
                ),
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    public function testPutNonExisting()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/content-providers/text/settings/4',
            array(
                'text_content_provider_settings' => array(
                    'page' => 5,
                    'section' => 2,
                    'text' => 'Text test'
                ),
            )
        );

        $this->assertTrue($this->client->getResponse()->isSuccessful());

        $settings = $this->em->getRepository('Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings')->find(2);

        $this->assertEquals('Footer', $settings->getPage()->getTitle());
        $this->assertEquals('Content', $settings->getSection()->getName());
        $this->assertEquals('Text test', $settings->getText());
    }

    public function testWrongPut()
    {
        $this->createTestClient();

        $crawler = $this->client->request(
            'PUT',
            '/api/content-providers/text/settings/1',
            array(
                'text_content_provider_settings' => array(
                    'name' => 'Test missing field',
                ),
            )
        );

        $this->assertEquals(400, $this->client->getResponse()->getStatusCode());
    }

    private function loadData()
    {
        $this->loadFixtures(array(
            'Webcook\Cms\CommonBundle\DataFixtures\ORM\LoadTextContentProviderSettingsData'
        ));
    }
}
