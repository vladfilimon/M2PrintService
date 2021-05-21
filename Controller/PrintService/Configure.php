<?php
namespace VladFilimon\M2PrintService\Controller\PrintService;

use Magento\Catalog\Api\Data\ProductInterfaceFactory;
use Magento\Catalog\Model\Product\Attribute\Source\Status;
use Magento\Catalog\Model\Product\Visibility;
use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Catalog\Model\Product\Media\Config;
use \Magento\Catalog\Model\Product\Attribute\Repository;
use Magento\Eav\Api\AttributeSetRepositoryInterface;
use \Magento\Eav\Model\Entity\Attribute\Set;
use \Magento\Framework\Image\Factory;
use \Magento\Framework\Module\Dir\Reader;
use \Magento\Framework\Session\SessionManagerInterface;

class Configure extends \Magento\Framework\App\Action\Action
{
    protected $_pageFactory;
    protected $_productFactory;
    protected $_fileSystem;
    protected $_fileUploaderFactory;
    protected $_mediaConfig;
    protected $_productAttributeRepository;
    protected $_attributeSetRepository;
    protected $_attributeSet;
    protected $_imageFactory;
    protected $_moduleReader;
    protected $_sessionManager;

    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\View\Result\PageFactory $pageFactory,
        ProductInterfaceFactory $productFactory,
        \Magento\Framework\Filesystem $fileSystem,
        \Magento\MediaStorage\Model\File\UploaderFactory $fileUploaderFactory,
        Config $mediaConfig,
        Repository $_productAttributeRepository,
        AttributeSetRepositoryInterface $attributeSetRepository,
        Set $attributeSet,
        Factory $imageFactory,
        Reader $moduleReader,
        SessionManagerInterface $sessionManager
    )
    {
        $this->_pageFactory = $pageFactory;
        $this->_productFactory = $productFactory;
        $this->_fileSystem = $fileSystem;
        $this->_fileUploaderFactory = $fileUploaderFactory;
        $this->_mediaConfig = $mediaConfig;
        $this->_productAttributeRepository = $_productAttributeRepository;
        $this->_attributeSetRepository = $attributeSetRepository;
        $this->_attributeSet = $attributeSet;
        $this->_imageFactory = $imageFactory;
        $this->_moduleReader = $moduleReader;
        $this->_sessionManager = $sessionManager;

        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->isPost() && $this->getRequest()->getFiles('file')) {
            $this->_sessionManager->start();
            $this->_sessionManager->setUserCropFile(base64_encode(file_get_contents($this->getRequest()->getFiles('file')['tmp_name'])));
        }
    }
}
