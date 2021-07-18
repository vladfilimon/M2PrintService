var config = {
    config: {
        mixins: {
            'Magento_ConfigurableProduct/js/catalog-add-to-cart': {
                'VladFilimon_M2PrintService/js/catalog-add-to-cart-mixin': true
            },
            'Magento_Swatches/js/swatch-renderer': {
                'VladFilimon_M2PrintService/js/swatch-renderer-mixin': true
            },
            'Magento_Catalog/js/gallery': {
                'VladFilimon_M2PrintService/js/gallery-mixin': true
            }
        }
    },
    paths: {
        'cropperjs' : ['//unpkg.com/cropperjs@latest/dist/cropper', 'VladFilimon_M2PrintService/cropper']
    },
    shim: {
        'cropperjs' : ['jquery']
    },
};
