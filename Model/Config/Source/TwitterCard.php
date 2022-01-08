<?php
namespace GDW\OpenGraph\Model\Config\Source;

class TwitterCard implements \Magento\Framework\Data\OptionSourceInterface
{
 public function toOptionArray()
 {
  return [
    ['value' => 'summary_large_image', 'label' => __('Summary Large Image')],
    ['value' => 'summary', 'label' => __('Summary')]
  ];
 }
}