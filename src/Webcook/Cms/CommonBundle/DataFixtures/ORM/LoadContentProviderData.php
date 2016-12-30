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

/**
 * ContentProvider fixtures for tests.
 */
class LoadContentProviderData implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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

        $this->addContentProvider('webcookcms.common.text_content_provider');
        
        $this->manager->flush();
    }

    private function addContentProvider(String $name)
    {
        $contentProvider = new ContentProvider();
        $contentProvider->setName($name);

        $this->manager->persist($contentProvider);
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 0;
    }
}
