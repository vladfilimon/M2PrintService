define(['jquery'],function ($) {
    'use strict';

    return function (widget) {
        $.widget('mage.SwatchRenderer', widget, {
            _loadMedia: function () {
                // no media load
            }
        });
        return $.mage.SwatchRenderer;
    };
});
