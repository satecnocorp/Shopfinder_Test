<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chalhoub\ShopFinder\Model\ShopFinder;

use Chalhoub\ShopFinder\Model\ResourceModel\ShopFinder\CollectionFactory;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

class DataProvider extends AbstractDataProvider
{

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var array
     */
    protected $loadedData;
    /**
     * @inheritDoc
     */
    protected $collection;

    protected $storeManager;


    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->storeManager = $storeManager;
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    /**
     * @inheritDoc
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $model) {
            $this->loadedData[$model->getshopfinderId()] = $model->getData();
            $img = [];
            if ($model->getshopfinderId()) {
                $img[0]['name'] = $model->getImage();
                $img[0]['url'] = $this->getMediaUrl().$model->getImage();
                $this->loadedData[$model->getId()]['Image'] = $img;
            }
        }

        $data = $this->dataPersistor->get('chalhoub_shopfinder_shopfinder');

        if (!empty($data)) {
            $model = $this->collection->getNewEmptyItem();
            $model->setData($data);
            $this->loadedData[$model->getId()] = $model->getData();
            $this->dataPersistor->clear('chalhoub_shopfinder_shopfinder');
        }

        return $this->loadedData;
    }

    public function getMediaUrl()
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'shopfinder/tmp/';
        return $mediaUrl;
    }
}

