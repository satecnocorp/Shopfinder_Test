<?php
namespace Chalhoub\Shopfinder\Api;

/**
 * Shop CRUD interface.
 * @api
 * @since 100.0.2
 */
interface ShopRepositoryInterface
{
    /**
     * Save shop.
     *
     * @param \Chalhoub\Shopfinder\Api\Data\ShopInterface $shop
     * @return \Chalhoub\Shopfinder\Api\Data\ShopInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(Data\ShopInterface $shop);

    /**
     * Retrieve shop.
     *
     * @param string $shopId
     * @return \Chalhoub\Shopfinder\Api\Data\ShopInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($shopId);
       /**
     * Retrieve shop.
     *
     * @param string $shopIdentifier
     * @return \Chalhoub\Shopfinder\Api\Data\ShopInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getByIdentifier($shopIdentifier);

    /**
     * Retrieve Shops matching the specified criteria.
     *
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Chalhoub\Shopfinder\Api\Data\ShopSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete shop.
     *
     * @param \Chalhoub\Shopfinder\Api\Data\ShopInterface $shop
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(Data\ShopInterface $shop);

    /**
     * Delete shop by ID.
     *
     * @param string $shopId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($shopId);
}
