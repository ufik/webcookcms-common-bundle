<?php

/**
 * This file is part of Webcook common bundle.
 *
 * See LICENSE file in the root of the bundle. Webcook 
 */

namespace Webcook\Cms\CommonBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Webcook\Cms\CoreBundle\Entity\ContentProvider;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Webcook\Cms\CommonBundle\Entity\TextContentProviderSettings;
use Webcook\Cms\CoreBundle\Entity\PageSection;

/**
 * ContentProvider fixtures for tests.
 */
class LoadTextContentProviderSettingsData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * System container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Entity manager.
     *
     * @var ObjectManager
     */
    private $manager;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->manager = $manager;

        $page    = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\Page')->findAll();
        $section = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\Section')->findAll();
        $contentProvider = $this->manager->getRepository('Webcook\Cms\CoreBundle\Entity\ContentProvider')->findAll();

        $textCPS = new TextContentProviderSettings;
        $textCPS->setPage($page[6])
            ->setSection($section[1])
            ->setText('<p>Test text</p>');

        $pageSection = new PageSection();
            $pageSection->setPage($page[6]);
            $pageSection->setSection($section[1]);
            $pageSection->setContentProvider($contentProvider[1]);

            $this->manager->persist($pageSection);

        $this->manager->persist($textCPS);
        
        $this->manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}
