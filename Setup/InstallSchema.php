<?php

namespace Jvi\Elcc\Setup;

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

        $tableName = $installer->getTable('elcc_data');
		// add elcc page data table
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

		// add elcc active data
		$tableName = $installer->getTable('cms_page');
		if ($connection->tableColumnExists($tableName, 'elcc_active') === false){
			$installer->getConnection()->addColumn(
			   $tableName,
			   'elcc_active',
			   [
				   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				   'length' => 1,
				   'nullable' => false,
				   'comment' => 'Is easy layout content controller active for this page?'
			   ]
		   );
	    }

		// add elcc page template data
		if ($connection->tableColumnExists($tableName, 'elcc_template') === false){
		   $installer->getConnection()->addColumn(
			  $tableName,
			  'elcc_template',
			  [
				  'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				  'length' => null,
				  'nullable' => false,
				  'comment' => 'Chosen template path'
			  ]
		  );
		}

        $installer->endSetup();
    }
}
