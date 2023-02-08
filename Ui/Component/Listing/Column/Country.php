<?php

namespace Chalhoub\ShopFinder\Ui\Component\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Framework\View\Element\UiComponentFactory;
use  Magento\Directory\Model\CountryFactory;
class Country extends Column
{

    protected $countryFactory;

    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CountryFactory $countryFactory,
        array $components = [],
        array $data = []
    ) {
        $this->countryFactory = $countryFactory;
        parent::__construct($context, $uiComponentFactory, $components, $data);
    }

    public function prepareDataSource(array $dataSource)
    {
        $dataSource = parent::prepareDataSource($dataSource);
        if (empty($dataSource['data']['items'])) {
            return $dataSource;
        }

        foreach ($dataSource['data']['items'] as &$item) {
            if (isset($item['Country']) && !empty($item['Country'])) {
                $item['Country'] = $this->countryFactory->create()->loadByCode($item['Country'])->getName();
            }
        }

        return $dataSource;
    }
}
