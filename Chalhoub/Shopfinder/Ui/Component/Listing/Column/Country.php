<?php

namespace Chalhoub\Shopfinder\Ui\Component\Listing\Column;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Directory\Model\Country as CountryModel;

class Country extends \Magento\Ui\Component\Listing\Columns\Column
{
    protected $countryModel;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Catalog\Helper\Image $imageHelper
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        CountryModel $countryModel,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->countryModel = $countryModel;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
 
            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['country']) && $item['country']) {
                    $item['country']= $this->countryModel->loadByCode($item['country'])->getName();
                }
            }
        

        return $dataSource;
    }
}