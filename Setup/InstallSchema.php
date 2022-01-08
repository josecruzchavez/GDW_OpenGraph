<?php
namespace GDW\OpenGraph\Setup;

use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\Setup\InstallSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class InstallSchema implements InstallSchemaInterface
{

    public function install(SchemaSetupInterface $setup, ModuleContextInterface $context)
    {

        $installer = $setup;
        $installer->startSetup();
        $connection = $installer->getConnection();
        
        $cmsPage = $setup->getTable('cms_page');

        if ($connection->tableColumnExists($cmsPage, 'opengraph_image') === false) {
            $connection->addColumn($cmsPage,'opengraph_image', ['type' => 'text', 'nullable' => true, 'comment' => 'og:image imagen 1200px x 630px']);
        }

        $installer->endSetup();
    }
}
