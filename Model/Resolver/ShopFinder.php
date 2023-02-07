<?php
 
declare(strict_types=1);
 
namespace Chalhoub\Shopfinder\Model\Resolver;
 
use Chalhoub\Shopfinder\Api\ShopRepositoryInterface;
use Chalhoub\Shopfinder\Model\Store\GetList;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\Resolver\Argument\SearchCriteria\Builder as SearchCriteriaBuilder;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
 
class ShopFinder implements ResolverInterface
{
 
    /**
     * @var GetListInterface
     */
    private $shopRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
 
    /**
     * PickUpStoresList constructor.
     * @param GetList $storeRepository
     * @param SearchCriteriaBuilder $searchCriteriaBuilder
     */
    public function __construct(ShopRepositoryInterface $shopRepository, SearchCriteriaBuilder $searchCriteriaBuilder)
    {
        $this->shopRepository = $shopRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }
 
    /**
     * @inheritdoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (!isset($args['pageSize']) ) {
            $args['pageSize']=0;
        }
        $this->vaildateArgs($args);
 
        $searchCriteria = $this->searchCriteriaBuilder->build('shops', $args);
        $searchCriteria->setCurrentPage($args['currentPage']);
        $searchCriteria->setPageSize($args['pageSize']);
        $searchResult = $this->shopRepository->getList($searchCriteria);
        return [
            'total_count' => $searchResult->getTotalCount(),
            'shops' => $searchResult->getItems(),
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
 
        if (isset($args['pageSize']) && $args['pageSize'] < 0) {
            throw new GraphQlInputException(__('pageSize value must be 0 or greater.'));
        }
    }
}