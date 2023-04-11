<?php
namespace GDW\OpenGraph\Model\Config\Source;

class ProductFields implements \Magento\Framework\Data\OptionSourceInterface
{

	private $_attributeCollectionFactory;

	public function __construct(\Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory)
	{
		$this->_attributeCollectionFactory = $attributeCollectionFactory;
	}

 	public function toOptionArray()
 	{
		$arr = [];
		$attributesCollection = $this->_attributeCollectionFactory->create();
		foreach ($attributesCollection as $attribute) {
			$arr[] = array(
				'value' => $attribute->getData('attribute_code'),
				'label' => $attribute->getData('frontend_label')
			);
		}
		return $arr;
 	}
}