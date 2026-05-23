<?php
namespace GDW\OpenGraph\Model\Config\Source;

use Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory;

class ProductFields implements \Magento\Framework\Data\OptionSourceInterface
{
	private CollectionFactory $_attributeCollectionFactory;

	public function __construct(CollectionFactory $attributeCollectionFactory)
	{
		$this->_attributeCollectionFactory = $attributeCollectionFactory;
	}

 	/**
 	 * @return array<int, array{value:mixed, label:mixed}>
 	 */
 	public function toOptionArray()
 	{
		$arr = [];
		$attributesCollection = $this->_attributeCollectionFactory->create();
		foreach ($attributesCollection as $attribute) {
			if (!is_object($attribute) || !method_exists($attribute, 'getData')) {
				continue;
			}
			$arr[] = array(
				'value' => $attribute->getData('attribute_code'),
				'label' => $attribute->getData('frontend_label')
			);
		}
		return $arr;
 	}
}