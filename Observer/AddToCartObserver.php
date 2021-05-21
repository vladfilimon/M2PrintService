<?php
namespace VladFilimon\M2PrintService\Observer;

use Magento\Framework\Event\ObserverInterface;
use \Magento\Framework\Session\SessionManagerInterface;
use \Magento\Catalog\Helper\Product\Configuration;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Catalog\Model\Webapi\Product\Option\Type\File\Processor as FileProcessor;
use Magento\Framework\Api\ImageContent;
use \Magento\Framework\Api\Data\ImageContentInterfaceFactory;

class AddToCartObserver implements ObserverInterface
{
    protected $_sessionManager;
    protected $_cfgHelper;
    protected $_logger;
    protected $_serializer;
    protected $_fileProcessor;
    protected $_imageContentFactory;

    public function __construct(SessionManagerInterface $sessionManager, Configuration $cfgHelper, \Psr\Log\LoggerInterface $logger, Json $serializer, FileProcessor $fileProcessor, ImageContentInterfaceFactory $imageContentFactory)
    {
        $this->_sessionManager = $sessionManager;
        $this->_cfgHelper = $cfgHelper;
        $this->_logger = $logger;
        $this->_serializer = $serializer;
        $this->_fileProcessor = $fileProcessor;
        $this->_imageContentFactory = $imageContentFactory;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $this->_sessionManager->start();
        $product =  $observer->getEvent()->getProduct();

        if(!$this->_sessionManager->getUserCropFile()) {
            return;
        }

        $imageContent = $this->_imageContentFactory->create();
        $imageContent->setBase64EncodedData($this->_sessionManager->getUserCropFile());
        $imageContent->setType("image/png");
        $imageContent->setName("test.png");

        /** @var \Magento\Framework\DataObject $buyRequest */
        $buyRequest = $observer->getEvent()->getBuyRequest();
        $transOptions  = $observer->getEvent()->getData('transport')->options;
        $options = $buyRequest->getData('options');

        $value = $this->_fileProcessor->processFileContent($imageContent);
        $value['label'] = 'Cropped';
        $value['value'] = 'file';

        $options['5'] = $value;
        $buyRequest->setData('options', $options);
        $transOptions['5']= $this->_serializer->serialize($value);
        $observer->getEvent()->getData('transport')->options = $transOptions;
        $this->_sessionManager->setUserCropFile(null);
    }
}
