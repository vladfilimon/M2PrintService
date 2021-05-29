<?php

namespace VladFilimon\M2PrintService\Plugin\CheckoutCart;

use \Magento\Framework\App\ObjectManager;

class Image
{
    /*
    protected $_objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->_objectManager = $objectManager;
    }
*/


    public function afterGetImage($item, $result)
    {
        if ($item->getProduct()->getData('sku') == 'tablou-canvas-personalizat')
        {
            foreach($item->getProduct()->getProductOptionsCollection() as $option) {
                if ($option->getTitle() == 'Fisier' && $option->getType() == 'file') {
                    var_dump($option->groupFactory($option->getType()),$option->getData('values'),$option->getValuesCollection()->toArray());die;
                }
            }
            die("END");
            var_dump($item->getProduct()->getCustomOption('option_4'));die;
        }
        //$result->setImageUrl( YOUR_IMAGE_URL );

        return $result;
    }
}
