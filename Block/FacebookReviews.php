<?php

namespace VladFilimon\M2PrintService\Block;

use Magento\Framework\HTTP\Client\Curl;
use Magento\Framework\View\Element\Template\Context;

class FacebookReviews extends \Magento\Framework\View\Element\Template
{
    protected Curl $curl;

    /**
     * Constructor
     *
     * @param Context $context
     * @param array $data
     */
    public function __construct(Context $context, array $data = [], Curl $curl)
    {
        $this->curl = $curl;
        parent::__construct($context, $data);
    }

    public function getReviews()
    {

        $apiUrl = 'https://graph.facebook.com/v11.0/atelieruldetablouri.ro/ratings?access_token=EAALuwL3zoRsBALWxQEVCQJFWZBBN3ijKovhavppdrjRfFlHbBhWAsGj7YbLaL42BEuuwYdZAXIc4CmOIioLN1HTo3RBLvEPHouTDDN0VkIljZBPPZBNhm5KY66cH9Jx5cvsAl1ElIDMDminC82ZCaNxM6h6WezBAZBPHWLZAynvTPnfQZBtZAGxoqLmOPjmDm3NQZD';
        $this->curl->get($apiUrl);
        $response = json_decode($this->curl->getBody(), true);
        return $response['data'];

    }
}
