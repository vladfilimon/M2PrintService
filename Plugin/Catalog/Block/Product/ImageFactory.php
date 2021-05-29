<?php

namespace VladFilimon\M2PrintService\Plugin\Catalog\Block\Product;
use Magento\Backend\Block\System\Store\Store;
use Magento\Catalog\Model\Product;
use Magento\Catalog\Block\Product\Image as ImageBlock;
use Magento\Store\Model\StoreManagerInterface;

class ImageFactory
{
    protected $_storeManager;

    public function __construct(StoreManagerInterface $storeManager)
    {
        $this->_storeManager = $storeManager;
    }

    public function afterCreate(\Magento\Catalog\Block\Product\ImageFactory $subject, ImageBlock $result, Product $product, string $imageId, array $attributes = null): ImageBlock
    {
        if ($product->getData('sku') == 'tablou-canvas-personalizat')
        {
            $mediaUrl = $this->_storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
            $imgUrl = $result->getData('image_url');

            $result->setData('image_url',
                $mediaUrl.mb_substr(
                    $imgUrl,
                    strpos($imgUrl,'custom_options')
                )
            );
        }

        return $result;
    }
}
