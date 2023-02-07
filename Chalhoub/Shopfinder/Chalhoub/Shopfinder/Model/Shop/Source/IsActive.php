<?php
namespace Chalhoub\Shopfinder\Model\Shop\Source;

use Magento\Framework\Data\OptionSourceInterface;

/**
 * Class IsActive
 */
class IsActive implements OptionSourceInterface
{
    /**
     * @var \Chalhoub\Shopfinder\Model\Shop
     */
    protected $shopfinderShop;

    /**
     * Constructor
     *
     * @param \Chalhoub\Shopfinder\Model\Shop $shopfinderShop
     */
    public function __construct(\Chalhoub\Shopfinder\Model\Shop $shopfinderShop)
    {
        $this->shopfinderShop = $shopfinderShop;
    }

    /**
     * Get options
     *
     * @return array
     */
    public function toOptionArray()
    {
        $availableOptions = $this->shopfinderShop->getAvailableStatuses();
        $options = [];
        foreach ($availableOptions as $key => $value) {
            $options[] = [
                'label' => $value,
                'value' => $key,
            ];
        }
        return $options;
    }
}
