<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Chalhoub\Shopfinder\Block\Adminhtml;

/**
 * Adminhtml cms blocks content block
 */
class Shop extends \Magento\Backend\Block\Widget\Grid\Container
{
    /**
     * @return void
     */
    protected function _construct()
    {
        $this->_blockGroup = 'Chalhoub_Shopfinder';
        $this->_controller = 'adminhtml_shop';
        $this->_headerText = __('Shops');
        $this->_addButtonLabel = __('Add New Shop');
        parent::_construct();
    }
}
