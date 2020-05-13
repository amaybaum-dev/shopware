<?php declare(strict_types=1);

namespace Shopware\Core\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;
use function Flag\next6050;

class Migration1587111506AddPausedScheduleToProductExport extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1587111506;
    }

    public function update(Connection $connection): void
    {
        if (next6050()) {
            $connection->executeUpdate('ALTER TABLE product_export ADD COLUMN paused_schedule TINYINT(1) NULL DEFAULT \'0\'');
        }
    }

    public function updateDestructive(Connection $connection): void
    {
    }
}
