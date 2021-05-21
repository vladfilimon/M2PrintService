<?php
\Magento\Framework\Component\ComponentRegistrar::register(
    \Magento\Framework\Component\ComponentRegistrar::MODULE,
    'VladFilimon_M2PrintService',
    __DIR__
);

\Magento\Framework\Component\ComponentRegistrar::register(\Magento\Framework\Component\ComponentRegistrar::THEME, 'frontend/VladFilimon/M2PrintService', __DIR__.'/view/frontend');
