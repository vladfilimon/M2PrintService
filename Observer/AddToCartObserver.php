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

       // $info = $observer->getEvent()->getData('info');
     //   $info['options'][5] = 'testme';//$this->_sessionManager->getUserCropFile();

        //$buyRequest = $observer->getEvent()->getData('buy_request');
        $product =  $observer->getEvent()->getProduct();

        /*
        $optionValue = [
            'type' =>'image/png',
          'title' => 'Cropped.PNG',
          'quote_path' =>'custom_options/quote/g/o/wbPF2CONKE7Fl7EHjeJRxnDEt0klKs4P',
          'order_path' => 'custom_options/order/g/o/wbPF2CONKE7Fl7EHjeJRxnDEt0klKs4P',
          'fullpath' => '/var/www/html/pub/media/custom_options/quote/g/o/wbPF2CONKE7Fl7EHjeJRxnDEt0klKs4P',
          'size' => '143218',
          'width' => 1914,
          'height' => 877,
          'secret_key' => '55b0a2cb1b85ef28851d'];
*/

        //$product->addCustomOption('additional_options', $this->_serializer->serialize([['label'=>'test','value'=>'value']]));

/*        file_put_contents('/tmp/vlad',$this->_sessionManager->getUserCropFile());
        return;*/

        $imageContent = $this->_imageContentFactory->create();
        $imageContent->setBase64EncodedData($this->_sessionManager->getUserCropFile());
        $imageContent->setType("image/png");
        $imageContent->setName("test.png");


        /** @var \Magento\Framework\DataObject $buyRequest */
        $buyRequest = $observer->getEvent()->getBuyRequest();
        $transOptions  = $observer->getEvent()->getData('transport')->options;
       // file_put_contents('/tmp/to', print_r($transOptions, true), FILE_APPEND);
        $options = $buyRequest->getData('options');

        //file_put_contents('/tmp/buyReq', print_r($buyRequest, true));
        //return;

        $value = $this->_fileProcessor->processFileContent($imageContent);
        $value['label'] = 'Cropped';
        $value['value'] = 'file';

       //$buyRequest['options'][5]= $value;


        //$product->addCustomOption('option_5', $this->_serializer->serialize($value));
        $options['5'] = $value;
        $buyRequest->setData('options', $options);
        $transOptions['5']= $this->_serializer->serialize($value);
        $observer->getEvent()->getData('transport')->options = $transOptions;

    }
}
