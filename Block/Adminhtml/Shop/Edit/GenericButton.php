<?php

namespace Chalhoub\Shopfinder\Block\Adminhtml\Shop\Edit;

use Magento\Backend\Block\Widget\Context;
use Chalhoub\Shopfinder\Api\ShopRepositoryInterface;
use Magento\Framework\Exception\NoSuchEntityException;

/**
 * Class GenericButton
 */
class GenericButton
{
    /**
     * @var Context
     */
    protected $context;

    /**
     * @var ShopRepositoryInterface
     */
    protected $shopRepository;

    /**
     * @param Context $context
     * @param ShopRepositoryInterface $shopRepository
     */
    public function __construct(
        Context $context,
        ShopRepositoryInterface $shopRepository
    ) {
        $this->context = $context;
        $this->shopRepository = $shopRepository;
    }

    /**
     * Return Shop ID
     *
     * @return int|null
     */
    public function getShopId()
    {
        try {
            return $this->shopRepository->getById(
                $this->context->getRequest()->getParam('shop_id')
            )->getId();
        } catch (NoSuchEntityException $e) {
        }
        return null;
    }

    /**
     * Generate url by route and parameters
     *
     * @param   string $route
     * @param   array $params
     * @return  string
     */
    
    public function getUrl($route = '', $params = [])
    {
        return $this->context->getUrlBuilder()->getUrl($route, $params);
    }
    
}
