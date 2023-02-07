<?php
namespace Chalhoub\Shopfinder\Model\Shop;

use Chalhoub\Shopfinder\Model\ResourceModel\Shop\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\Modifier\PoolInterface;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class DataProvider
 */
class DataProvider extends \Magento\Ui\DataProvider\ModifierPoolDataProvider
{
    /**
     * @var \Chalhoub\Shopfinder\Model\ResourceModel\Shop\Collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;

    /**
     * Constructor
     *
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $shopCollectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     * @param PoolInterface|null $pool
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        StoreManagerInterface $storeManager,
        CollectionFactory $shopCollectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = [],
        PoolInterface $pool = null
    ) {
        $this->storeManager = $storeManager;
        $this->collection = $shopCollectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data, $pool);
    }

    /**
     * Get data
     *
     * @return array
     */

    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        /** @var \Chalhoub\Shopfinder\Model\Shop $shop */
        foreach ($items as $shop) {
            $this->loadedData[$shop->getId()] = $shop->getData();
            if ($shop->getImage()) {
                $m['image'][0]['name'] = $shop->getImage();
                $m['image'][0]['url'] = $this->getMediaUrl($shop->getImage());
                $fullData = $this->loadedData;
                $this->loadedData[$shop->getId()] = array_merge($fullData[$shop->getId()], $m);
            }
        }

        $data = $this->dataPersistor->get('shopfinder_shop');
        if (!empty($data)) {
            $shop = $this->collection->getNewEmptyItem();
            $shop->setData($data);
            $this->loadedData[$shop->getId()] = $shop->getData();
            $this->dataPersistor->clear('shopfinder_shop');
        }

        return $this->loadedData;
    }
    public function getMediaUrl($path = '')
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'wysiwyg/shopfinder/' . $path;
        return $mediaUrl;
    }
}
