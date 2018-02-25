<?php

namespace Jvi\Elcc\Setup;

use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
    public function upgrade(SchemaSetupInterface $installer, ModuleContextInterface $context)
    {
        $installer->startSetup();
        $tableName = $installer->getTable('elcc_data');
        if (version_compare($context->getVersion(), '1.0.1') < 0) {
	        if ($installer->getConnection()->isTableExists($tableName) != true) {
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
	                    'target_id',
	                    Table::TYPE_TEXT,
	                    null,
	                    ['nullable' => false, 'default' => ''],
	                    'Target ID'
	                )
	                ->addColumn(
	                    'type',
	                    Table::TYPE_TEXT,
	                    null,
	                    ['nullable' => false, 'default' => ''],
	                    'Type'
	                )
	                ->addColumn(
	                    'data',
	                    Table::TYPE_TEXT,
	                    null,
	                    ['nullable' => false, 'default' => ''],
	                    'Data'
	                )
	                ->setComment('Easy layout content control dataset')
	                ->setOption('type', 'InnoDB')
	                ->setOption('charset', 'utf8');
	            $installer->getConnection()->createTable($table);
	        }

	        $installer->endSetup();
        }


        $installer->endSetup();

    }
}
