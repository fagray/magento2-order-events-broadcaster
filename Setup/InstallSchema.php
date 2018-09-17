<?php

namespace Rayms\OrderEventsBroadcaster\Setup;

use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class InstallSchema implements InstallSchemaInterface
{
    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {
        $installer = $setup;
        $installer->startSetup();

        // Get the table
        $tableName = $installer->getTable('order_events_broadcaster');
        // Check if the table already exists
        if (!$installer->getConnection()->isTableExists($tableName)) {
            // Create the table if not exist yet
            $table = $installer->getConnection()
                ->newTable($tableName)
                ->addColumn(
                    'id',
                    Table::TYPE_INTEGER,
                    null,
                    [
                        'identity' => true,
                        'unsigned' => true,
                        'nullable' => false,
                        'primary' => true
                    ],
                    'ID'
                )
                ->addColumn(
                    'status_mode',
                    Table::TYPE_TEXT,
                    20,
                    ['nullable' => true, 'default' => 'production'],
                    'Status Mode'
                )
                ->addColumn(
                    'webhook_secret',
                    Table::TYPE_TEXT,
                    150,
                    ['nullable' => true, 'default' => 'mysecret'],
                    'Webhook Secret'
                )
                ->addColumn(
                    'created_at',
                    Table::TYPE_DATETIME,
                    null,
                    ['nullable' => false],
                    'Created At'
                )
                ->setComment('Order events broadcaster table');
              
            $installer->getConnection()->createTable($table);
        }

        $installer->endSetup();
    }
}