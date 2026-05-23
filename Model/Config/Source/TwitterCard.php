<?php
namespace GDW\OpenGraph\Model\Config\Source;

class TwitterCard implements \Magento\Framework\Data\OptionSourceInterface
{
 /**
  * @return array<int, array{value:string, label:\Magento\Framework\Phrase}>
  */
 public function toOptionArray()
 {
  return [
    ['value' => 'summary_large_image', 'label' => __('Summary Large Image')],
    ['value' => 'summary', 'label' => __('Summary')]
  ];
 }
}