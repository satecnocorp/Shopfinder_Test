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
 
class CreateShop implements ResolverInterface
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
        $success=false;
        $message="there was a problem trying to update the shop";
        $this->vaildateArgs($args);
        $fields=$args['input'];
        $shop=$this->shopRepository->getByIdentifier($fields['identifier']);
        foreach($fields as $key=>$field)
        {
            $setter="set".ucwords($key);
           $shop->$setter($field);
        }
        if($shop->save())
        {
            $success=true;
            $message="Shop saved successfully";
        }
       
        return [
            'success' => $success,
            'message'=> $message
        ];
    }  
 
    /**
     * @param array $args
     * @throws GraphQlInputException
     */
    private function vaildateArgs(array $args): void
    {
        if (!isset($args['input']) ) {
            throw new GraphQlInputException(__('Input can not be empty'));
        }
        if (isset($args['input']) && !$args['input']['identifier'] ) {
            throw new GraphQlInputException(__('Identifier cannot be empty'));
        }
    }
}