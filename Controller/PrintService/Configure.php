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
        Reader $moduleReader
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

        parent::__construct($context);
    }

    public function execute()
    {
        if ($this->getRequest()->isPost()) {

            $frameTypeAttr = $this->_productAttributeRepository->get('frame_type');
            $attributeSet = $this->_attributeSet->load('user_pictures_test','attribute_set_name');

            srand(microtime(true));
            $product = $this->_productFactory->create();
            $product->setTypeId('configurable')
                ->setAttributeSetId($attributeSet->getAttributeSetId())
                ->setWebsiteIds([1])
                ->setName('Config Product -'.microtime())
                ->setSku('config-'.microtime())
                ->setPrice(50)
                ->setVisibility(Visibility::VISIBILITY_BOTH)
                ->setStatus(Status::STATUS_ENABLED)
                ->setStockData(
                    [
                        'use_config_manage_stock' => 1,
                        'qty' => 1,
                        'is_qty_decimal' => 0,
                        'is_in_stock' => 1,
                    ]
                );

            $product->getTypeInstance()->setUsedProductAttributeIds([$frameTypeAttr->getAttributeId()], $product); //attribute ID of attribute 'size_general' in my store

            $configurableAttributesData = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);

            $product->setCanSaveConfigurableAttributes(true);
            $product->setConfigurableAttributesData($configurableAttributesData);
            $product->setConfigurableProductsData([]);




            $mediaDirectory = $this->_fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
            $baseTmpMediaPath = $this->_mediaConfig->getBaseTmpMediaPath();
            $mediaDirectory->create($baseTmpMediaPath);

            $mediaPath = $mediaDirectory->getAbsolutePath($baseTmpMediaPath);

            copy($_FILES['image']['tmp_name'], $mediaPath. '/product_image.png');
            $product->addImageToMediaGallery( $mediaPath. '/product_image.png', array('image', 'small_image', 'thumbnail'), false, false);
            $product->save();



            $associatedProductIds = [];


        foreach($frameTypeAttr->getOptions() as $option) {
            $imageFactory = $this->_imageFactory->create($mediaPath. '/product_image.png');
            if ($option->getValue()) {
                $imageFactory->open();

                //die("OK");

                    $moduleDir = $this->_moduleReader->getModuleDir(
                        \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
                        'VladFilimon_M2PrintService'
                    );

                    $imageFactory->watermark(
                        $moduleDir.'/frontend/web/images/TEST.png',
                        100,
                        100,
                        80,
                        false
                    );
                    $imageFactory->setWatermarkWidth(100);
                    $imageFactory->setWatermarkHeight(100);
                    $imageFactory->rotate(90);

                $imageFactory->setWatermarkPosition('stretch');
                $imageFactory->setWatermarkImageOpacity(90);

                $imageFactory->save($mediaPath,'product_image_watermark_'.$option->getValue().'.png');

                $simpleProduct = $this->_productFactory->create();

             //   die("DACA");
                $simpleProduct->addImageToMediaGallery( $mediaPath.DS.'product_image_watermark_'.$option->getValue().'.png', array('image', 'small_image', 'thumbnail','swatch_image'), false, false);

                $simpleProduct->setTypeId('simple')
                    ->setAttributeSetId($attributeSet->getAttributeSetId())
                    ->setWebsiteIds([1])
                    ->setName('Simple Product -' . microtime())
                    ->setSku('simple-' . microtime())
                    ->setPrice(50)
                    ->setVisibility(Visibility::VISIBILITY_IN_CATALOG)
                    ->setStatus(Status::STATUS_ENABLED)
                    ->setStockData(
                        [
                            'use_config_manage_stock' => 1,
                            'qty' => 1,
                            'is_qty_decimal' => 0,
                            'is_in_stock' => 1,
                        ]
                    );

                $simpleProduct->setData('frame_type', $option->getValue());
                $simpleProduct->save();
                $associatedProductIds[] = $simpleProduct->getId();

            }
        }

        $product->setAffectConfigurableProductAttributes();
        $product->setCanSaveConfigurableAttributes(true);
        $product->setNewVariationsAttributeSetId($attributeSet->getAttributeSetId());
        $product->setAssociatedProductIds($associatedProductIds);
        $product->save();





            return $this->_redirect($product->getProductUrl());

           // $productRepository->save($product);
        }
        return $this->_pageFactory->create();
    }
}
