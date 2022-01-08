<?php
namespace GDW\OpenGraph\Plugin;

use Magento\Cms\Model\Page;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Request\Http;
use GDW\Core\Helper\Data as HelperData;
use Magento\Framework\View\Page\Config\Renderer;
use Magento\Framework\View\Page\Config as PageConfig;

class SeoRender
{
    protected $page;
    protected $request;
    protected $helpData;
    protected $eavConfig;
    protected $pageConfig;

    public function __construct(
        Page $page,
        Http $request,
        Config $eavConfig,  
        HelperData $helpData,
        PageConfig $pageConfig
    )
    {
        $this->page = $page;
        $this->request = $request;
        $this->eavConfig = $eavConfig;
        $this->helperData = $helpData;
        $this->pageConfig = $pageConfig; 
    }

    public function beforeRenderMetadata(Renderer $subject)
    {
        $fullActionname = $this->request->getFullActionName();

        switch ($fullActionname) {
            case 'catalog_product_view':
                $this->getProductOpenGraph();
                break;
            case 'catalog_category_view':
                $this->getCategoryOpenGraph();
                break;
            case 'cms_index_index':
                $this->getHomeOpenGraph();
                break;
            case 'cms_page_view':
                $this->getPagesOpenGraph();    
                break;
        }
    }

    public function getGeneralOpenGraph()
    {
        $this->pageConfig->setMetadata('og:locale', $this->helperData->getConfigValue('general/locale/code'));
        $this->pageConfig->setMetadata('og:site_name', $this->helperData->getConfigValue('general/store_information/name'));
    }

    public function getGeneralTwitterOpenGraph()
    {
        $twitterCard = $this->helperData->getConfigValue('gdw/seo_opengraph/twitter_card') ?? 'summary_large_image';

        $this->pageConfig->setMetadata('twitter:card', $twitterCard);
        $this->pageConfig->setMetadata('twitter:site', $this->helperData->getConfigValue('gdw/seo_opengraph/twitter_site'));
        $this->pageConfig->setMetadata('twitter:creator', $this->helperData->getConfigValue('gdw/seo_opengraph/twitter_creator'));
    }

    public function getHomeOpenGraph()
    {
        $image = $this->page->getOpengraphImage() ?? $this->helperData->getConfigValue('gdw/seo_opengraph/image_base');
        
        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'website');
        $this->pageConfig->setMetadata('og:title', $this->page->getMetaTitle());
        $this->pageConfig->setMetadata('og:description', $this->page->getMetaDescription());
        $this->pageConfig->setMetadata('og:url', $this->helperData->getStoreData()->getBaseUrl());
        $this->pageConfig->setMetadata('og:image', $image);

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $this->page->getMetaTitle());
        $this->pageConfig->setMetadata('twitter:description', $this->page->getMetaDescription());
        $this->pageConfig->setMetadata('twitter:url', $this->helperData->getStoreData()->getBaseUrl());
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getPagesOpenGraph(){
        $image = $this->page->getOpengraphImage() ?? $this->helperData->getConfigValue('gdw/seo_opengraph/image_base');
        
        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'website');
        $this->pageConfig->setMetadata('og:title', $this->page->getMetaTitle());
        $this->pageConfig->setMetadata('og:description', $this->page->getMetaDescription());
        $this->pageConfig->setMetadata('og:url', $this->helperData->getStoreData()->getUrl($this->page->getIdentifier()));
        $this->pageConfig->setMetadata('og:image', $image);

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $this->page->getMetaTitle());
        $this->pageConfig->setMetadata('twitter:description', $this->page->getMetaDescription());
        $this->pageConfig->setMetadata('twitter:url', $this->helperData->getStoreData()->getUrl($this->page->getIdentifier()));
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getCategoryOpenGraph(){
        $category = $this->helperData->getCurrentCategory();
        $image = $category->getOpengraphImage() ?? $this->helperData->getConfigValue('gdw/seo_opengraph/image_base');

        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'website');
        $this->pageConfig->setMetadata('og:title', $category->getMetaTitle());
        $this->pageConfig->setMetadata('og:description', $category->getMetaDescription());
        $this->pageConfig->setMetadata('og:url', $category->getUrl());
        $this->pageConfig->setMetadata('og:image', $image);

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $category->getMetaTitle());
        $this->pageConfig->setMetadata('twitter:description', $category->getMetaDescription());
        $this->pageConfig->setMetadata('twitter:url', $category->getUrl());
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getProductOpenGraph(){
        $product = $this->helperData->getCurrentProduct();
        $image = $this->getImageOfProduct($product);
        $productBrand = $this->getProductBrand($product);
        $productCondition = $this->getProductCondition($product);
        $currency = $this->helperData->getStoreData()->getCurrentCurrencyCode() ?? 'USD';
                 
        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'product');
        $this->pageConfig->setMetadata('og:title', $product->getMetaTitle());
        $this->pageConfig->setMetadata('og:description', $product->getMetaDescription());
        $this->pageConfig->setMetadata('og:url', $product->getProductUrl());
        $this->pageConfig->setMetadata('og:image', $image);
        $this->pageConfig->setMetadata('product:price:amount', $product->getFinalPrice());
        $this->pageConfig->setMetadata('product:price:currency', $currency);
        $this->pageConfig->setMetadata('product:condition', $productCondition);
        $this->pageConfig->setMetadata('product:brand', $productBrand);

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $product->getMetaTitle());
        $this->pageConfig->setMetadata('twitter:description', $product->getMetaDescription());
        $this->pageConfig->setMetadata('twitter:url', $product->getProductUrl());
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getImageOfProduct($product)
    {
        $image = $this->helperData->getConfigValue('gdw/seo_opengraph/image_base');
            if($product->getOpengraphImage() != null || $product->getOpengraphImage() != ''){
                $image = $product->getOpengraphImage();
            }else{
                if($product->getImage() && $product->getImage() != 'no_selection') {
                    $image = $this->helperData->getStoreData()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'catalog/product'.$product->getImage();
                }
            }
        return $image;
    }

    public function getProductBrand($product)
    {
        $productBrand = null;
        $useProductBrand = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_brand_status') ?? 0;
        if($useProductBrand == 1){
            $ProductBrandAttribute = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_brand') ?? 'brand';
            try {
                $typeOfAtttributeBrand = $this->eavConfig->getAttribute('catalog_product', $ProductBrandAttribute);
                if($typeOfAtttributeBrand->getData('frontend_input') == 'select'):
                    $productBrand = $product->getAttributeText($ProductBrandAttribute);
                else:
                    $productBrand = $product->getData($ProductBrandAttribute);
                endif;
            } catch (\Throwable $th) {
                $this->helperData->log($th);
            }
        }
        return $productBrand;
    }

    public function getProductCondition($product)
    {
        $productCondition = null;
        $useProductCondition = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_condition_status') ?? 0;
        if($useProductCondition == 1){
            $ProductConditionAttribute = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_condition') ?? 'condition';
            try {
                $typeOfAtttributeCondition = $this->eavConfig->getAttribute('catalog_product', $ProductConditionAttribute);
                
                if($typeOfAtttributeCondition->getData('frontend_input') == 'select'):
                    $productCondition = $product->getAttributeText($ProductConditionAttribute);
                else:
                    $productCondition = $product->getData($ProductConditionAttribute);
                endif;
            } catch (\Throwable $th) {
                $this->helperData->log($th);
            }
        }
        return $productCondition;
    }
}
