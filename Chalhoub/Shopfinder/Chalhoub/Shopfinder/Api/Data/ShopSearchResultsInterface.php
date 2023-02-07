<?php
namespace Chalhoub\Shopfinder\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Interface for cms Shop search results.
 * @api
 * @since 100.0.2
 */
interface ShopSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get shops list.
     *
     * @return \Chalhoub\Shopfinder\Api\Data\ShopInterface[]
     */
    public function getItems();

    /**
     * Set shops list.
     *
     * @param \Chalhoub\Shopfinder\Api\Data\ShopInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
