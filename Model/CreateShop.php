<?php

namespace Chalhoub\ShopFinder\Model;

use Chalhoub\ShopFinder\Api\Data\ShopFinderInterface;
use Chalhoub\ShopFinder\Api\Data\ShopFinderInterfaceFactory;
use Chalhoub\ShopFinder\Api\ShopFinderRepositoryInterface;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use  Magento\Store\Model\StoreManagerInterface;

class CreateShop
{

    /**
     * @var DataObjectHelper
     */
    private $dataObjectHelper;
    /**
     * @var ShopFinderInterface
     */
    private $shopFinderRepository;
    /**
     * @var ShopFinderInterface
     */
    private $shopFinderFactory;
    private $storeManager;


    /**
     * @param DataObjectHelper $dataObjectHelper
     * @param ShopFinderInterface $shopFinderRepository
     * @param ShopFinderInterfaceFactory $shopFinderInterfaceFactory
     */
    public function __construct(
        DataObjectHelper $dataObjectHelper,
        ShopFinderRepositoryInterface $shopFinderRepository,
        ShopFinderInterfaceFactory $shopFinderInterfaceFactory,
        StoreManagerInterface $storeManager
    ) {
        $this->dataObjectHelper = $dataObjectHelper;
        $this->shopFinderRepository = $shopFinderRepository;
        $this->shopFinderFactory = $shopFinderInterfaceFactory;
        $this->storeManager = $storeManager;
    }

    /**
     * @param array $data
     * @return ShopFinderInterface
     * @throws GraphQlInputException
     */
    public function execute(array $data): ShopFinderInterface
    {
        try {
            $this->vaildateData($data);
            $shop = $this->createShop($data);
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }

        return $shop;
    }

    /**
     * Guard function to handle bad request.
     * @param array $data
     * @throws LocalizedException
     */
    private function vaildateData(array $data)
    {
        if (!isset($data[ShopFinderInterface::SHOP_NAME])) {
            throw new LocalizedException(__('Name must be set'));
        }
    }

    /**
     * @param array $data
     * @return ShopFinderInterface
     * @throws CouldNotSaveException
     */
    private function createShop(array $data): ShopFinderInterface
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'shopfinder/tmp/';
        /** @var ShopFinderInterface $shopFinderDataObject */
        $shopDataObject = $this->shopFinderFactory->create();
        $shopDataObject->load($data["Identifier"], "Identifier");

        if($shopDataObject->getShopfinderId()) {
            $shopDataObject->setShopName($data["shop_name"]);
            $shopDataObject->setCountry($data["Country"]);
            $shopDataObject->setImage($data["Image"]);
        } else {
            $this->dataObjectHelper->populateWithArray(
                $shopDataObject,
                $data,
                ShopFinderInterface::class
            );
        }
        $this->shopFinderRepository->save($shopDataObject);
        $shopDataObject->setImage($mediaUrl.$shopDataObject->getImage());
        return $shopDataObject;
    }
}
