<?php

namespace GDW\OpenGraph\Setup;

use \Magento\Eav\Model\Config;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\Category;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Setup\CategorySetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;

/**
 * Class InstallData
 * @package Magepow\Sitemapexclusion\Setup
 */
class InstallData implements InstallDataInterface
{
    protected $eavConfig;
    protected $eavSetupFactory;
    protected $categorySetupFactory;
    
    public function __construct(
        Config $eavConfig,
        EavSetupFactory $eavSetupFactory,
        CategorySetupFactory $categorySetupFactory
    ) {
        $this->eavConfig = $eavConfig;
        $this->eavSetupFactory = $eavSetupFactory;
        $this->categorySetupFactory = $categorySetupFactory;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        
        /* Product attributes */
        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        if(!$this->isAttributeExists(Product::ENTITY, 'opengraph_image')){
            $eavSetup->addAttribute(Product::ENTITY, 'opengraph_image', [
                'type' => 'text',
                'backend' => '',
                'frontend' => '',
                'label' => 'OpenGraph Image',
                'note' => 'URL of absolute image, Recommended size 1200px x 640px',
                'input' => 'text',
                'class' => '',
                'source' => '',
                'global' => ScopedAttributeInterface::SCOPE_GLOBAL,
                'visible' => true,
                'required' => false,
                'user_defined' => true,
                'default' => '',
                'unique' => false,
                'searchable' => false,
                'filterable' => false,
                'comparable' => false,
                'visible_on_front' => false,
                'used_in_product_listing' => false,
                'is_used_in_grid' => true,
                'is_filterable_in_grid' => true,
                'apply_to' => 'simple,configurable,bundle,virtual',
                'group' => 'Search Engine Optimization'
            ]);
        }

    /* Category attribute */
        $categorySetup = $this->categorySetupFactory->create(['setup' => $setup]);
        if(!$this->isAttributeExists(Category::ENTITY, 'opengraph_image')){
            $categorySetup->addAttribute(Category::ENTITY, 'opengraph_image', 
                [
                    'type'      => 'text',
                    'label'     => 'OpenGraph Image',
                    'note' => 'URL of absolute image, Recommended size 1200px x 640px',
                    'input'     => 'text',
                    'sort_order' => 301,
                    'source'    => '',
                    'global'    => ScopedAttributeInterface::SCOPE_GLOBAL,
                    'visible'   => true,
                    'required'  => false,
                    'user_defined' => true,
                    'default'   => null,
                    'group'     => 'Search Engine Optimization',
                    'backend'   => ''
                ]);
        }

        $setup->endSetup();
    }

    public function isAttributeExists($type, $field)
    {
        $attr = $this->eavConfig->getAttribute($type, $field);
        return ($attr && $attr->getId());
    }
}
