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
        return;
        $setup->startSetup();
        $this->state->setAreaCode('frontend');
        $entityType = $this->entityTypeFactory->create()->loadByCode('catalog_product');
        $defaultSetId = $entityType->getDefaultAttributeSetId();

        $attributeSet = $this->setFactory->create();

        $attributeSet->setData([
            'attribute_set_name' => 'user_canvas_'.microtime(),
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_aspect_canvas',
            [
                'type' => 'int',
                'label' => 'Frame aspect',
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_aspect_multicanvas',
            [
                'type' => 'int',
                'label' => 'Frame aspect',
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_dimensions_canvas_rectangle',
            [
                'type' => 'int',
                'label' => 'Frame dimensions',
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_dimensions_canvas_square',
            [
                'type' => 'int',
                'label' => 'Frame dimensions',
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_dimensions_canvas_panorama',
            [
                'type' => 'int',
                'label' => 'Frame dimensions',
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_dimensions_multicanvas_3',
            [
                'type' => 'int',
                'label' => 'Frame dimensions',
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_dimensions_multicanvas_5',
            [
                'type' => 'int',
                'label' => 'Frame dimensions',
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
        );

        $eavSetup->addAttribute(
            \Magento\Catalog\Model\Product::ENTITY,
            'frame_border',
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
                'update_product_preview_image' => false
            ]
        );


        $attributesOptionsData = [
            'frame_type' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => 'Canvas'
                        ],
                        'option_1' => [
                            0 => 'Multi-Canvas'
                        ],
                    ],
                ],
                'swatchvisual' => [
                    'value' => [
                        'option_0' => 'canvas.png',
                        'option_1' => 'multicanvas.png',
                    ],
                ],
            ],
            'frame_aspect_canvas' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => 'Patrat'
                        ],
                        'option_1' => [
                            0 => 'Dreptunghi'
                        ],
                        'option_2' => [
                            0 => 'Panorama'
                        ],
                    ],
                ],
            ],
            'frame_aspect_multicanvas' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => '3 tablouri'
                        ],
                        'option_1' => [
                            0 => '5 tablouri'
                        ],
                    ],
                ],
            ],
            'frame_dimensions_canvas_rectangle' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => '20x30'
                        ],
                        'option_1' => [
                            0 => '30x40'
                        ],
                        'option_2' => [
                            0 => '30x50'
                        ],
                        'option_3' => [
                            0 => '35x50'
                        ],
                        'option_4' => [
                            0 => '40x60'
                        ],
                        'option_5' => [
                            0 => '50x70'
                        ],
                        'option_6' => [
                            0 => '50x80'
                        ],
                        'option_7' => [
                            0 => '60x90'
                        ],
                        'option_8' => [
                            0 => '70x100'
                        ],
                        'option_9' => [
                            0 => '80x120'
                        ],
                    ],
                ]
            ],
            'frame_dimensions_canvas_square' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => '25x25'
                        ],
                        'option_1' => [
                            0 => '30x30'
                        ],
                        'option_2' => [
                            0 => '40x40'
                        ],
                        'option_3' => [
                            0 => '50x50'
                        ],
                        'option_4' => [
                            0 => '60x60'
                        ],
                        'option_5' => [
                            0 => '70x70'
                        ],
                        'option_6' => [
                            0 => '80x80'
                        ],
                        'option_7' => [
                            0 => '100x100'
                        ],
                    ],
                ]
            ],
            'frame_dimensions_canvas_panorama' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => '20x40'
                        ],
                        'option_1' => [
                            0 => '20x50'
                        ],
                        'option_2' => [
                            0 => '30x60'
                        ],
                        'option_3' => [
                            0 => '40x80'
                        ],
                        'option_4' => [
                            0 => '50x100'
                        ],
                        'option_5' => [
                            0 => '60x120'
                        ],
                    ],
                ]
            ],
            'frame_dimensions_multicanvas_3' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => '20x40 + 2* 20x30'
                        ],
                        'option_1' => [
                            0 => '3* 20x40'
                        ],
                        'option_2' => [
                            0 => '3* 30x70'
                        ],
                        'option_3' => [
                            0 => '30x70 + 2* 30x50'
                        ],
                        'option_4' => [
                            0 => '3* 30x60'
                        ],
                    ],
                ]
            ],

            'frame_dimensions_multicanvas_5' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => 'Standard'
                        ],
                    ],
                ]
            ],
            'frame_border' => [
                \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_KEY => \Magento\Swatches\Model\Swatch::SWATCH_INPUT_TYPE_VISUAL,
                'optionvisual' => [
                    'value' => [
                        'option_0' => [
                            0 => 'Plina'
                        ],
                        'option_1' => [
                            0 => 'Alba'
                        ],
                        'option_2' => [
                            0 => 'Neagra'
                        ],
                    ],
                ],
                'swatchvisual' => [
                    'value' => [
                        'option_0' => 'frame_border_full.png',
                        'option_1' => 'frame_border_white.png',
                        'option_2' => 'frame_border_black.png'
                    ],
                ],
            ],
        ];

        $mediaDirectory = $this->filesystem->getDirectoryRead(\Magento\Framework\App\Filesystem\DirectoryList::MEDIA);
        $tmpMediaPath = $this->productMediaConfig->getBaseTmpMediaPath();
        $fullTmpMediaPath = $mediaDirectory->getAbsolutePath($tmpMediaPath);

        foreach ($attributesOptionsData as &$attributeOptionsData) {
            if (isset($attributeOptionsData['swatchvisual']['value'])) {
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
        }

        $setAttributeOptionsKeys = [
            'frame_type',
            'frame_aspect_canvas',
            'frame_aspect_multicanvas',
            'frame_border',
            'frame_dimensions_canvas_rectangle',
            'frame_dimensions_canvas_square',
            'frame_dimensions_canvas_panorama',
            'frame_dimensions_multicanvas_3',
            'frame_dimensions_multicanvas_5',
        ];

        foreach ($setAttributeOptionsKeys as $key)
        {
            /* @var \Magento\Catalog\Model\ResourceModel\Eav\Attribute $attribute */
            $attribute = $this->attributeRepository->get($key);
            $attribute->addData($attributesOptionsData[$key]);
            $attribute->save();
        }

        $group_id = $this->catalogConfig->getAttributeGroupId($attributeSet->getId(), 'Product Details');
        foreach($attributesOptionsData as $attrCode => $attributeOptionsData) {
            $this->attributeManagement->assign(
                'catalog_product',
                $attributeSet->getId(),
                $group_id,
                $attrCode,
                999
            );
        }
/*
        $this->attributeManagement->assign(
            'catalog_product',
            $attributeSet->getId(),
            $group_id,
            'color',
            999
        );*/

        $setup->endSetup();


    }

}
