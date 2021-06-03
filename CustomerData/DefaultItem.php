<?php

namespace VladFilimon\M2PrintService\CustomerData;

class DefaultItem extends \Magento\Checkout\CustomerData\DefaultItem
{
    protected function hasProductUrl()
    {
        if ($this->item->getProduct()->getData('sku') == 'tablou-canvas-personalizat') {
            return false;
        }
        return parent::hasProductUrl();
    }

    protected function doGetItemData()
    {
        $toReturn = parent::doGetItemData();
        if ($this->item->getProduct()->getData('sku') == 'tablou-canvas-personalizat') {
            $toReturn['is_visible_in_site_visibility'] = false;
        }

        return $toReturn;
    }
}
