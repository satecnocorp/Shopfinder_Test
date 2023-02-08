<?php

namespace Chalhoub\ShopFinder\Model\Resolver;

use Chalhoub\ShopFinder\Api\ShopFinderRepositoryInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
use  Magento\Store\Model\StoreManagerInterface;

class FetchShop implements ResolverInterface
{

    /**
     * @var ShopFinderRepositoryInterface
     */
    private $shopFinderRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    private $storeManager;

    /**
     * ShopFinder constructor.
     * @param ShopFinderRepositoryInterface $shopFinderRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(ShopFinderRepositoryInterface $shopFinderRepository,
                                SearchCriteriaBuilder $searchCriteriaBuilder,
                                StoreManagerInterface $storeManager
    )
    {
        $this->shopFinderRepository = $shopFinderRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->storeManager = $storeManager;
    }

    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {

        $this->vaildateArgs($args);
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'shopfinder/tmp/';

        $searchCriteria = $this->searchCriteriaBuilder->build('shop_finder', $args);
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);
        $searchResult = $this->shopFinderRepository->getList($searchCriteria);
        foreach ($searchResult->getItems() as $item) {
            $item->setImage($mediaUrl.$item->getImage());
        }
        return [
            'total_count' => $searchResult->getTotalCount(),
            'items' => $searchResult->getItems(),
        ];
    }

    /**
     * @param array $args
     * @throws GraphQlInputException
     */
    private function vaildateArgs(array $args): void
    {
        if (isset($args['currentPage']) && $args['currentPage'] < 1) {
            throw new GraphQlInputException(__('currentPage value must be greater than 0.'));
        }

        if (isset($args['pageSize']) && $args['pageSize'] < 1) {
            throw new GraphQlInputException(__('pageSize value must be greater than 0.'));
        }
    }
}
