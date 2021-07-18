/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery'
], function ($) {
    "use strict";

    return function (widget) {
        $.widget('mage.gallery', widget, {

            initialize: function (config, element) {
                console.log('initialize called');
            },

            initGallery: function () {
                console.log('initGallery');
            },

            openFullScreen: function () {
                console.log('openFullScreen');
            }

        });

        return $.mage.gallery;
    };
});
