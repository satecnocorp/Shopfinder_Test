<?php

namespace Chalhoub\ShopFinder\Model\Resolver;

use Chalhoub\ShopFinder\Model\UpdateShop as UpdateShopService;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class UpdateShop implements ResolverInterface
{
    /**
     * @var UpdateShopService
     */
    private $updateShop;

    /**
     * @param UpdateShopService $updateShop
     */
    public function __construct(UpdateShopService $updateShop)
    {
        $this->updateShop = $updateShop;
    }

    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {

        if (empty($args['id'])) {
            throw new GraphQlInputException(__('Shop "id" value must be specified'));
        }

        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('"input" value must be specified'));
        }

        return ['shop' => $this->updateShop->execute($args['id'], $args['input'])];
    }
}
