<?php

namespace VladFilimon\M2PrintService\Plugin;

use \Magento\Framework\App\ObjectManager;
use \Magento\Catalog\Helper\Product\Configuration;
use \Magento\Framework\Serialize\Serializer\Json;

class QuoteItemPlugin
{
    protected $productConfigurationHelper = null;
    protected $serializer;

    public function __construct(
        Configuration $productConfigurationHelper,
        Json $serializer

    ) {
        $this->productConfigurationHelper = $productConfigurationHelper;
        $this->serializer = $serializer;
    }

    public function afterGetProduct($item, $result)
    {
        if ($result->getData('sku') == 'tablou-canvas-personalizat') {
            foreach($result->getProductOptionsCollection() as $option) {
                if ($option->getTitle() == 'Fisier' && $option->getType() == 'file') {

                    $itemOption = $item->getOptionByCode('option_' . $option->getId());
                    $group = $option->groupFactory($option->getType())
                    ->setOption($option)
                    ->setConfigurationItem($item)
                    ->setConfigurationItemOption($itemOption);
                    $values = $this->serializer->unserialize($itemOption->getValue());

                    $result->setData('small_image', $values['quote_path']);
                    $result->setData('thumbnail', $values['quote_path']);

                }

            }
        }

        return $result;
    }
}
