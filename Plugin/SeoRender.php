<?php
namespace GDW\OpenGraph\Plugin;

use Magento\Catalog\Model\Category;
use Magento\Catalog\Model\Product;
use Magento\Cms\Model\Page;
use Magento\Eav\Model\Config;
use Magento\Framework\App\Request\Http;
use GDW\Core\Helper\Data as HelperData;
use Magento\Framework\View\Page\Config\Renderer;
use Magento\Framework\View\Page\Config as PageConfig;

class SeoRender
{
    protected Page $page;
    protected Http $request;
    protected HelperData $helperData;
    protected Config $eavConfig;
    protected PageConfig $pageConfig;

    public function __construct(
        Page $page,
        Http $request,
        Config $eavConfig,  
        HelperData $helperData,
        PageConfig $pageConfig
    )
    {
        $this->page = $page;
        $this->request = $request;
        $this->eavConfig = $eavConfig;
        $this->helperData = $helperData;
        $this->pageConfig = $pageConfig; 
    }

    public function beforeRenderMetadata(Renderer $subject): void
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

    public function getGeneralOpenGraph(): void
    {
        $this->pageConfig->setMetadata('og:locale', $this->toString($this->helperData->getConfigValue('general/locale/code')));
        $this->pageConfig->setMetadata('og:site_name', $this->toString($this->helperData->getConfigValue('general/store_information/name')));
    }

    public function getGeneralTwitterOpenGraph(): void
    {
        $twitterCard = $this->helperData->getConfigValue('gdw/seo_opengraph/twitter_card') ?? 'summary_large_image';

        $this->pageConfig->setMetadata('twitter:card', $this->toString($twitterCard));
        $this->pageConfig->setMetadata('twitter:site', $this->toString($this->helperData->getConfigValue('gdw/seo_opengraph/twitter_site')));
        $this->pageConfig->setMetadata('twitter:creator', $this->toString($this->helperData->getConfigValue('gdw/seo_opengraph/twitter_creator')));
    }

    public function getHomeOpenGraph(): void
    {
        $image = $this->toString($this->page->getOpengraphImage() ?? $this->helperData->getConfigValue('gdw/seo_opengraph/image_base'));
        $baseUrl = $this->getBaseUrl();
        
        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'website');
        $this->pageConfig->setMetadata('og:title', $this->toString($this->page->getMetaTitle()));
        $this->pageConfig->setMetadata('og:description', $this->toString($this->page->getMetaDescription()));
        $this->pageConfig->setMetadata('og:url', $baseUrl);
        $this->pageConfig->setMetadata('og:image', $image);

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $this->toString($this->page->getMetaTitle()));
        $this->pageConfig->setMetadata('twitter:description', $this->toString($this->page->getMetaDescription()));
        $this->pageConfig->setMetadata('twitter:url', $baseUrl);
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getPagesOpenGraph(): void
    {
        $image = $this->toString($this->page->getOpengraphImage() ?? $this->helperData->getConfigValue('gdw/seo_opengraph/image_base'));
        $pageIdentifier = $this->toString($this->page->getIdentifier());
        $pageUrl = $this->getUrlForPath($pageIdentifier);
        
        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'website');
        $this->pageConfig->setMetadata('og:title', $this->toString($this->page->getMetaTitle()));
        $this->pageConfig->setMetadata('og:description', $this->toString($this->page->getMetaDescription()));
        $this->pageConfig->setMetadata('og:url', $pageUrl);
        $this->pageConfig->setMetadata('og:image', $image);

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $this->toString($this->page->getMetaTitle()));
        $this->pageConfig->setMetadata('twitter:description', $this->toString($this->page->getMetaDescription()));
        $this->pageConfig->setMetadata('twitter:url', $pageUrl);
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getCategoryOpenGraph(): void
    {
        $category = $this->helperData->getCurrentCategory();
        if (!$category instanceof Category) {
            return;
        }

        $image = $this->toString($category->getOpengraphImage() ?? $this->helperData->getConfigValue('gdw/seo_opengraph/image_base'));

        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'website');
        $this->pageConfig->setMetadata('og:title', $this->toString($category->getMetaTitle()));
        $this->pageConfig->setMetadata('og:description', $this->toString($category->getMetaDescription()));
        $this->pageConfig->setMetadata('og:url', $this->toString($category->getUrl()));
        $this->pageConfig->setMetadata('og:image', $image);

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $this->toString($category->getMetaTitle()));
        $this->pageConfig->setMetadata('twitter:description', $this->toString($category->getMetaDescription()));
        $this->pageConfig->setMetadata('twitter:url', $this->toString($category->getUrl()));
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getProductOpenGraph(): void
    {
        $product = $this->helperData->getCurrentProduct();
        if (!$product instanceof Product) {
            return;
        }

        $image = $this->getImageOfProduct($product);
        $productBrand = $this->getProductBrand($product);
        $productCondition = $this->getProductCondition($product);
        $currency = $this->toString($this->helperData->getConfigValue('currency/options/default') ?? 'USD');
                 
        $this->getGeneralOpenGraph();
        $this->pageConfig->setMetadata('og:type', 'product');
        $this->pageConfig->setMetadata('og:title', $this->toString($product->getMetaTitle()));
        $this->pageConfig->setMetadata('og:description', $this->toString($product->getMetaDescription()));
        $this->pageConfig->setMetadata('og:url', $this->toString($product->getProductUrl()));
        $this->pageConfig->setMetadata('og:image', $image);
        $this->pageConfig->setMetadata('product:price:amount', (string) $product->getFinalPrice());
        $this->pageConfig->setMetadata('product:price:currency', $currency);
        $this->pageConfig->setMetadata('product:condition', $productCondition ?? '');
        $this->pageConfig->setMetadata('product:brand', $productBrand ?? '');

        $this->getGeneralTwitterOpenGraph();
        $this->pageConfig->setMetadata('twitter:title', $this->toString($product->getMetaTitle()));
        $this->pageConfig->setMetadata('twitter:description', $this->toString($product->getMetaDescription()));
        $this->pageConfig->setMetadata('twitter:url', $this->toString($product->getProductUrl()));
        $this->pageConfig->setMetadata('twitter:image', $image); 
    }

    public function getImageOfProduct(Product $product): string
    {
        $image = $this->toString($this->helperData->getConfigValue('gdw/seo_opengraph/image_base') ?? '');
            if ($product->getOpengraphImage() != null || $product->getOpengraphImage() != '') {
                $image = $this->toString($product->getOpengraphImage());
            } else {
                if ($product->getImage() && $product->getImage() != 'no_selection') {
                    $image = $this->getUrlForPath('media/catalog/product' . $this->toString($product->getImage()));
                }
            }
        return $image;
    }

    public function getProductBrand(Product $product): ?string
    {
        $productBrand = null;
        $useProductBrand = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_brand_status') ?? 0;
        if($useProductBrand == 1){
            $ProductBrandAttribute = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_brand') ?? 'brand';
            if (!is_scalar($ProductBrandAttribute)) {
                return null;
            }
            $brandAttribute = (string) $ProductBrandAttribute;
            try {
                $typeOfAtttributeBrand = $this->eavConfig->getAttribute('catalog_product', $brandAttribute);
                if ($typeOfAtttributeBrand->getData('frontend_input') == 'select') {
                    $productBrand = $product->getAttributeText($brandAttribute);
                } else {
                    $productBrand = $product->getData($brandAttribute);
                }
            } catch (\Throwable $th) {
                $this->helperData->log($th);
            }
        }
        return is_scalar($productBrand) ? (string) $productBrand : null;
    }

    public function getProductCondition(Product $product): ?string
    {
        $productCondition = null;
        $useProductCondition = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_condition_status') ?? 0;
        if($useProductCondition == 1){
            $ProductConditionAttribute = $this->helperData->getConfigValue('gdw/seo_opengraph/facebook_condition') ?? 'condition';
            if (!is_scalar($ProductConditionAttribute)) {
                return null;
            }
            $conditionAttribute = (string) $ProductConditionAttribute;
            try {
                $typeOfAtttributeCondition = $this->eavConfig->getAttribute('catalog_product', $conditionAttribute);
                
                if ($typeOfAtttributeCondition->getData('frontend_input') == 'select') {
                    $productCondition = $product->getAttributeText($conditionAttribute);
                } else {
                    $productCondition = $product->getData($conditionAttribute);
                }
            } catch (\Throwable $th) {
                $this->helperData->log($th);
            }
        }
        return is_scalar($productCondition) ? (string) $productCondition : null;
    }

    private function toString(mixed $value): string
    {
        if (is_scalar($value)) {
            return (string) $value;
        }

        return '';
    }

    private function getBaseUrl(): string
    {
        $baseUrl = $this->helperData->getConfigValue('web/unsecure/base_url');
        return $this->toString($baseUrl);
    }

    private function getUrlForPath(string $path): string
    {
        $baseUrl = rtrim($this->getBaseUrl(), '/');
        $path = ltrim($path, '/');
        if ($baseUrl === '') {
            return $path;
        }

        return $baseUrl . '/' . $path;
    }
}
