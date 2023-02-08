<?php

namespace Chalhoub\ShopFinder\Model\Resolver;

use Magento\Framework\GraphQl\Exception\GraphQlAuthorizationException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class DeleteShop implements ResolverInterface
{

    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        if (empty($args) || !is_array($args)) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }
        throw new GraphQlAuthorizationException(__('The current user isn\'t authorized to delete shop.'));
    }

}
