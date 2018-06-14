<?php declare(strict_types=1);

namespace Shopware\Core\Content\Configuration\Event;

use Shopware\Core\Content\Configuration\ConfigurationGroupDefinition;
use Shopware\Core\Framework\ORM\Write\WrittenEvent;

class ConfigurationGroupWrittenEvent extends WrittenEvent
{
    public const NAME = 'configuration_group.written';

    public function getName(): string
    {
        return self::NAME;
    }

    public function getDefinition(): string
    {
        return ConfigurationGroupDefinition::class;
    }
}
