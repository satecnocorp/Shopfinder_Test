<?php
namespace Chalhoub\Shopfinder\Model\ResourceModel\Shop;

use Chalhoub\Shopfinder\Api\Data\ShopInterface;
use Chalhoub\Shopfinder\Model\ResourceModel\AbstractCollection;

/**
 * Shop Collection
 */
class Collection extends AbstractCollection
{
    /**
     * @var string
     */
    protected $_idFieldName = 'shop_id';

    /**
     * Event prefix
     *
     * @var string
     */
    protected $_eventPrefix = 'shopfinder_shop_collection';

    /**
     * Event object
     *
     * @var string
     */
    protected $_eventObject = 'shop_collection';

    /**
     * Perform operations after collection load
     *
     * @return $this
     */
    protected function _afterLoad()
    {
        //$entityMetadata = $this->metadataPool->getMetadata(ShopInterface::class);
        return parent::_afterLoad();
    }

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(\Chalhoub\Shopfinder\Model\Shop::class, \Chalhoub\Shopfinder\Model\ResourceModel\Shop::class);

    }

    /**
     * Returns pairs shop_id - title
     *
     * @return array
     */
    public function toOptionArray()
    {
        return $this->_toOptionArray('shop_id', 'name');
    }

    /**
     * Add filter by store
     *
     * @param int|array|\Magento\Store\Model\Store $store
     * @param bool $withAdmin
     * @return $this
     */
    public function addStoreFilter($store, $withAdmin = true)
    {
        //$this->performAddStoreFilter($store, $withAdmin);

        return $this;
    }

}
