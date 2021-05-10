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
        if ($this->getRequest()->isPost()) {
            $this->_sessionManager->start();
            $this->_sessionManager->setUserCropFile(base64_encode(file_get_contents($this->getRequest()->getFiles('file')['tmp_name'])));
        }
    }
    public function executeOld()
    {
        if ($this->getRequest()->isPost()) {

            $frameTypeAttr = $this->_productAttributeRepository->get('frame_type');
            $frameAspectCanvas = $this->_productAttributeRepository->get('frame_aspect_canvas');
            $frameAspectMulticanvas = $this->_productAttributeRepository->get('frame_aspect_multicanvas');
            $frameDimensionsCanvasRectangle = $this->_productAttributeRepository->get('frame_dimensions_canvas_rectangle');
            $frameDimensionsCanvasSquare = $this->_productAttributeRepository->get('frame_dimensions_canvas_square');
            $frameDimensionsCanvasPanorama = $this->_productAttributeRepository->get('frame_dimensions_canvas_panorama');
            $frameDimensionsMulticanvas3 = $this->_productAttributeRepository->get('frame_dimensions_multicanvas_3');
            $frameDimensionsMulticanvas5 = $this->_productAttributeRepository->get('frame_dimensions_multicanvas_5');
            $frameBorder = $this->_productAttributeRepository->get('frame_border');

            $frameDimensionsAttr = $this->_productAttributeRepository->get('frame_dimensions');


            $attributeSet = $this->_attributeSet->load('user_canvas_0.85576000 1618991469','attribute_set_name');

            srand(microtime(true));
            $product = $this->_productFactory->create();
            $product->setTypeId('configurable')
                ->setAttributeSetId($attributeSet->getAttributeSetId())
                ->setWebsiteIds([1])
                ->setName('Config Product -'.microtime())
                ->setSku('config-'.microtime())
                ->setPrice(50) //@todo remove
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

            $product->getTypeInstance()->setUsedProductAttributeIds([
                $frameTypeAttr->getAttributeId(),
                $frameDimensionsAttr->getAttributeId(),
            ], $product); //attribute ID of attribute 'size_general' in my store

            $configurableAttributesData = $product->getTypeInstance()->getConfigurableAttributesAsArray($product);

            $product->setCanSaveConfigurableAttributes(true);
            $product->setConfigurableAttributesData($configurableAttributesData);
            $product->setConfigurableProductsData([]);

            $mediaDirectory = $this->_fileSystem->getDirectoryWrite(DirectoryList::MEDIA);
            $baseTmpMediaPath = $this->_mediaConfig->getBaseTmpMediaPath();
            $mediaDirectory->create($baseTmpMediaPath);
            $mediaPath = $mediaDirectory->getAbsolutePath($baseTmpMediaPath);
            copy($_FILES['image']['tmp_name'], $mediaPath. '/product_image.png');
            $product->addImageToMediaGallery( $mediaPath. '/product_image.png', array('image', 'small_image', 'thumbnail'), false, true);

            $product->save();

            $associatedProductIds = [];
            $passes = 0;

        foreach($frameTypeAttr->getOptions() as $option) {
            if (!$option->getValue()) {
                continue;
            }

            if($option->getLabel() == 'Multi-Canvas') {
                continue;
            }

            $currentFrameAspectCanvas = $frameAspectCanvas;


            foreach ($currentFrameAspectCanvas->getOptions() as $frameAspectCanvasOption) {
                if (empty(trim($frameAspectCanvasOption->getLabel()))) {
                    continue;
                }
                switch($frameAspectCanvasOption->getLabel()){
                    case 'Patrat':
                        $currentFrameDimensions = $frameDimensionsCanvasSquare;
                        break;
                    case 'Dreptunghi':
                        $currentFrameDimensions = $frameDimensionsCanvasRectangle;
                        break;
                    case 'Panorama':
                        $currentFrameDimensions = $frameDimensionsCanvasPanorama;
                        break;
                    default:
                        throw new \Exception("Unknown frame_aspect_canvas option: {$frameAspectCanvasOption->getLabel()}");

                }

                foreach ($currentFrameDimensions->getOptions() as $dimensionOption) {
                    if ($dimensionOption->getValue() == '') {
                        continue;
                    }

                    $passes++;
                    preg_match('/([0-9]+)\s*x\s*([0-9]+).*/', $dimensionOption->getLabel(), $matches);


                    //$imageFactory = $this->_imageFactory->create($mediaPath . '/product_image.png');
                    //$imageFactory->open();

                    $moduleDir = $this->_moduleReader->getModuleDir(
                        \Magento\Framework\Module\Dir::MODULE_VIEW_DIR,
                        'VladFilimon_M2PrintService'
                    );

                    /*
                    $imageFactory->watermark(
                        $moduleDir . '/frontend/web/images/' . $option->getLabel() .'.png',
                        100,
                        100,
                        80,
                        false
                    );
                    */

                    /*
                    $finalRatio = $matches[1] / $matches[2];
                    $workingWidth =  $imageFactory->getOriginalWidth() ;
                    $workingHeight = $imageFactory->getOriginalHeight();

                    $originalRatio = $imageFactory->getOriginalWidth() / $imageFactory->getOriginalHeight();

                    if($workingWidth / $workingHeight > $finalRatio) {
                        $workingWidth = $workingHeight * $finalRatio;
                    } else {
                        $workingHeight = $workingWidth / $finalRatio;
                    }

                    $imageFactory->keepAspectRatio(false);
                    $imageFactory->resize($workingWidth, $workingHeight);
                    $imageFactory->save($mediaPath, 'product_image_watermark_' . $option->getValue() . ' '.$option->getLabel().'.png');
                    */
                    foreach ($frameBorder->getOptions() as $frameBorderOption) {
                        $simpleProduct = $this->_productFactory->create();
                        //$simpleProduct->addImageToMediaGallery($mediaPath . DS . 'product_image_watermark_' . $option->getValue() . ' '.$option->getLabel().'.png', array('image', 'small_image', 'thumbnail', 'swatch_image'), false, false);

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

                        $simpleProduct->setData($frameTypeAttr->getAttributeCode(), $option->getValue());
                        $simpleProduct->setData($currentFrameAspectCanvas->getAttributeCode(), $frameAspectCanvasOption->getValue());
                        //$simpleProduct->setData($currentFrameAspectCanvas->getAttributeCode(), $currentFrameAspectCanvas->getValue());
                        $simpleProduct->setData($currentFrameDimensions->getAttributeCode(), $currentFrameDimensions->getValue());
                        $simpleProduct->setData($frameBorderOption->getAttributeCode(), $frameBorderOption->getValue());

                        $simpleProduct->save();
                        $associatedProductIds[] = $simpleProduct->getId();
                    }
                }
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
