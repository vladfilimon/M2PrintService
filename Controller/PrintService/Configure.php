<?php
namespace VladFilimon\M2PrintService\Controller\PrintService;

class Configure extends \Magento\Framework\App\Action\Action
{

    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context
    )
    {

        parent::__construct($context);
    }

    /**
     * View  page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        die("CONFIGURE");
        return $result->setData($data);
    }
}