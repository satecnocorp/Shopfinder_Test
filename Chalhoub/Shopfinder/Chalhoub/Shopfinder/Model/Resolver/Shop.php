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
 
class Shop implements ResolverInterface
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
         $this->vaildateArgs($args);

        $identifier= $args['identifier'];
        $result=[$this->shopRepository->getByIdentifier($identifier)];
        return [
            'shop' => $result,
        ];
    }  
 
    /**
     * @param array $args
     * @throws GraphQlInputException
     */
    private function vaildateArgs(array $args): void
    {
        if (isset($args['identifier']) && !$args['identifier'] ) {
            throw new GraphQlInputException(__('Identifier cannot be empty'));
        }
    }
}