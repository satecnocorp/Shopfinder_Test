<?php

namespace Chalhoub\ShopFinder\Model\Resolver;

use Chalhoub\ShopFinder\Model\CreateShop as CreateShopService;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CreateShop implements ResolverInterface
{
    /**
     * @var CreateShopService
     */
    private $createShop;

    /**
     * @param CreateShopService $createShop
     */
    public function __construct(CreateShopService $createShop)
    {
        $this->createShop = $createShop;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }


        return ['shop' => $this->createShop->execute($args['input'])];
    }
}
