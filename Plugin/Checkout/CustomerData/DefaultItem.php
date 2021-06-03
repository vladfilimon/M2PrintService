<?php

namespace VladFilimon\M2PrintService\Plugin\Checkout\CustomerData;

use Magento\Catalog\Helper\Product\Configuration;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote\Item;
use Magento\Store\Model\StoreManagerInterface;

class DefaultItem
{
    protected $productConfigurationHelper = null;
    protected $serializer;
    protected $storeManager;

    public function __construct(
        Configuration $productConfigurationHelper,
        Json $serializer,
        StoreManagerInterface $storeManager

    ) {
        $this->productConfigurationHelper = $productConfigurationHelper;
        $this->serializer = $serializer;
        $this->storeManager = $storeManager;
    }

    public function afterGetItemData($subject, $result, Item $item)
    {
        if ($item->getProduct()->getData('sku') == 'tablou-canvas-personalizat') {
            foreach($item->getProduct()->getProductOptionsCollection() as $option) {
                if ($option->getTitle() == 'Fisier' && $option->getType() == 'file') {

                    $itemOption = $item->getOptionByCode('option_' . $option->getId());
                    $group = $option->groupFactory($option->getType())
                        ->setOption($option)
                        ->setConfigurationItem($item)
                        ->setConfigurationItemOption($itemOption);
                    $values = $this->serializer->unserialize($itemOption->getValue());

                    $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
                    $result['product_image']['src'] = $mediaUrl.$values['quote_path'];
                }

            }
        }

        return $result;
    }

    public function afterHasProductUrl($subject, $result)
    {
        return false;
    }

    public function afterGetRedirectUrl($subject, $result)
    {
        return false;
    }
}
