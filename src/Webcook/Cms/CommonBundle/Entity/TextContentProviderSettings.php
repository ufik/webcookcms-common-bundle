<?php

namespace Webcook\Cms\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Webcook\Cms\CommonBundle\Base\BasicEntity;
use Webcook\Cms\CoreBundle\Entity\ContentProviderSettings;

/**
 * Content provider settings text entity.
 *
 * @ORM\Entity()
 */
class TextContentProviderSettings extends ContentProviderSettings
{
    /** @ORM\Column(type="text") */
    private $text;

    public function getText()
    {
        return $this->text;
    }

    public function setText($text)
    {
        $this->text = $text;

        return $this;
    }
}
