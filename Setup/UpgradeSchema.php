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
        }

		if (version_compare($context->getVersion(), '1.0.2') < 0) {
			$installer->getConnection()->addColumn(
			   $tableName,
			   'active',
			   [
				   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
				   'length' => 1,
				   'nullable' => false,
				   'comment' => 'Is active'
			   ]
		   );

		   $installer->getConnection()->addColumn(
			  $tableName,
			  'template',
			  [
				  'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
				  'length' => null,
				  'nullable' => false,
				  'comment' => 'Chosen template path'
			  ]
		  );
		}

		if (version_compare($context->getVersion(), '1.0.3') < 0) {
			$tableName = $installer->getTable('cms_page');

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

		if (version_compare($context->getVersion(), '1.0.4') < 0) {
			$installer->getConnection()->dropColumn(
			   $tableName,
			   'active',
			   $schemaName = null
		   );

		   $installer->getConnection()->dropColumn(
			  $tableName,
			  'template',
			  $schemaName = null
		  );
		}

		if (version_compare($context->getVersion(), '1.0.5') < 0) {
			$tableName = $installer->getTable('cms_page');

			$installer->getConnection()->addColumn(
			   $tableName,
			   'elcc_generated',
			   [
				   'type' => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
 				   'length' => null,
				   'nullable' => false,
				   'comment' => 'The chosen template with all the template literals replaced / removed.'
			   ]
		   );
		}

        $installer->endSetup();

    }
}
