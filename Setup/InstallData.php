<?php

namespace VladFilimon\M2PrintService\Setup;

use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Eav\Model\AttributeSetManagement;
use Magento\Eav\Model\Entity\Attribute\SetFactory;
use Magento\Eav\Model\Entity\TypeFactory;
use Magento\Framework\Filesystem;
use Magento\Catalog\Model\Product\Media\Config;
use \Magento\Swatches\Helper\Media;
use \Magento\Catalog\Model\Product\Attribute\Repository;
use \Magento\Eav\Api\AttributeManagementInterface;
use \Magento\Catalog\Model\Config as CatalogConfig;
use \Magento\Framework\Filesystem\Driver\File;
use \Magento\Framework\App\State;

class InstallData implements InstallDataInterface
{
    private $eavSetupFactory;
    private $setFactory;
    private $attrSetManagement;
    private $entityTypeFactory;
    private $filesystem;
    private $productMediaConfig;
    private $swatchHelper;
    private $attributeRepository;
    private $attributeManagement;
    private $catalogConfig;
    private $driverFile;
    private $state;

    public function __construct(
        EavSetupFactory $eavSetupFactory,
        SetFactory $setFactory,
        AttributeSetManagement $attrSetManagement,
        TypeFactory $entityTypeFactory,
        Filesystem $filesystem,
        Config $productMediaConfig,
        Media $swatchHelper,
        Repository $attributeRepository,
        AttributeManagementInterface $attributeManagement,
        CatalogConfig $catalogConfig,
        File $driverFile,
        State $state
    )
    {
        $this->eavSetupFactory = $eavSetupFactory;
        $this->setFactory = $setFactory;
        $this->attrSetManagement = $attrSetManagement;
        $this->entityTypeFactory = $entityTypeFactory;
        $this->filesystem = $filesystem;
        $this->productMediaConfig = $productMediaConfig;
        $this->swatchHelper = $swatchHelper;
        $this->attributeRepository = $attributeRepository;
        $this->attributeManagement = $attributeManagement;
        $this->catalogConfig = $catalogConfig;
        $this->driverFile = $driverFile;
        $this->state = $state;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();
        $this->state->setAreaCode('frontend');
        $entityType = $this->entityTypeFactory->create()->loadByCode('catalog_product');
        $defaultSetId = $entityType->getDefaultAttributeSetId();

        $attributeSet = $this->setFactory->create();

        $attributeSet->setData([
            'attribute_set_name' => 'user_pictures_test',
            'entity_type_id' => $entityType->getId(),
            'sort_order' => 3,
        ]);

        $this->attrSetManagement->create('catalog_product', $attributeSet, $defaultSetId);


        $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_type',
            [
                'type' => 'int',
                'label' => 'Frame type',
                'input' => 'select',
                'backend' => 'Magento\Eav\Model\Entity\Attribute\Backend\ArrayBackend',
                'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Table',
                'required' => false,
                'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
                'group' => 'Product Details',
                'used_in_product_listing' => true,
                'visible_on_front' => true,
                'user_defined' => true,
                'filterable' => 2,
                'filterable_in_search' => true,
                'used_for_promo_rules' => true,
                'is_html_allowed_on_front' => true,
                'used_for_sort_by' => true,
                'update_product_preview_image' => true
            ]
        /*
        [
            // table eav_attribute
            'backend_type' => 'int',
            'frontend_input' => 'select',
            'frontend_label' => ['Frame Type'],
            // 'frontend_class' => NULL,
            // 'source_model' => NULL,
            'is_required' => 1,
            'is_user_defined' => 0,
            // 'default_value' =>
            'is_unique' => 0,

            // table catalog_eav_attribute
            'is_global' => 1,
            'is_visible' => 1,
            'is_searchable' => 0,
            'is_filterable' => 0,
            'is_comparable' => 0,
            'is_visible_on_front' => 0,//empty($attribute_meta['visible']) ? 0 : 1,
            'is_html_allowed_on_front' => 1,
            'is_used_for_price_rules' => 1,
            'is_filterable_in_search' => 0,
            'used_in_product_listing' => 0,
            'used_for_sort_by' => 0,
            // 'apply_to' => NULL,
            'is_visible_in_advanced_search' => 0,
            // 'position' => 0,
            'is_wysiwyg_enabled' => 0,
            'is_used_for_promo_rules' => 1,
            'is_required_in_admin_store' => 1,
            'is_used_in_grid' => 0,
            'is_visible_in_grid' => 0,
            'is_filterable_in_grid' => 0,
            // 'search_weight' => 1,
        ]*/
        /*
        [
            'type' => 'text',
            'backend' => '',
            'frontend' => '',
            'label' => 'Sample Atrribute',
            'input' => 'text',
            'class' => '',
            'source' => '',
            'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
            'visible' => true,
            'required' => true,
            'user_defined' => false,
            'default' => '',
            'searchable' => false,
            'filterable' => false,
            'comparable' => false,
            'visible_on_front' => false,
            'used_in_product_listing' => true,
            'unique' => false,
            'apply_to' => ''
        ]*/
        );


        $attributesOptionsData = [
            'frame_type' => [
            \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
            'optionvisual' => [
                'value' => [
                    'option_0' => [
                        0 => 'SIMPLE'
                    ],
                    'option_1' => [
                        0 => 'MIXED'
                    ],
                ],
            ],
            'swatchvisual' => [
                'value' => [
                    'option_0' => 'SimpleFrame.jpg',
                    'option_1' => 'MixedFrame.jpg',
                ],
            ],
            ]
        ];

        $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $tmpMediaPath = $this->productMediaConfig->getBaseTmpMediaPath();
        $fullTmpMediaPath = $mediaDirectory->getAbsolutePath($tmpMediaPath);

        foreach ($attributesOptionsData as &$attributeOptionsData) {
            $swatchVisualFiles = $attributeOptionsData['swatchvisual']['value'];
            foreach ($swatchVisualFiles as $index => $swatchVisualFile) {

                $this->driverFile->copy(
                    __DIR__ . DIRECTORY_SEPARATOR . 'swatchvisual' . DIRECTORY_SEPARATOR . 'frame_type' . DIRECTORY_SEPARATOR . $swatchVisualFile,
                    $fullTmpMediaPath . DIRECTORY_SEPARATOR . $swatchVisualFile
                );

                $newFile = $this->swatchHelper->moveImageFromTmp($swatchVisualFile);
                if (substr($newFile, 0, 1) == '.') {
                    $newFile = substr($newFile, 1); // Fix generating swatch variations for files beginning with ".".
                }

                $this->swatchHelper->generateSwatchVariations($newFile);

                $attributeOptionsData['swatchvisual']['value'][$index] = $newFile;
            }
        }


            /* @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
            $attribute = $this->attributeRepository->get('frame_type');
            $attribute->addData($attributesOptionsData['frame_type']);
            $attribute->save();

        $group_id = $this->catalogConfig->getAttributeGroupId($attributeSet->getId(), 'Product Details');
        $this->attributeManagement->assign(
            'catalog_product',
            $attributeSet->getId(),
            $group_id,
            'frame_type',
            999
        );

        $setup->endSetup();


    }

}
