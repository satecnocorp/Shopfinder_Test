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

class UpdateShop
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
     * @param int $id
     * @param array $data
     * @return ShopFinderInterface
     * @throws GraphQlInputException
     */
    public function execute(int $id, array $data): ShopFinderInterface
    {

        try {
            $shop = $this->updateShop($id, $data);
        } catch (LocalizedException $e) {
            throw new GraphQlInputException(__($e->getMessage()));
        }

        return $shop;
    }


    /**
     * int $id
     * @param array $data
     * @return ShopFinderInterface
     * @throws CouldNotSaveException
     */
    private function updateShop(int $id, array $data): ShopFinderInterface
    {
        $mediaUrl = $this->storeManager->getStore()
                ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA).'shopfinder/tmp/';

        /** @var ShopFinderInterface $shopFinderDataObject */
        $shopDataObject = $this->shopFinderFactory->create();
        $shopDataObject->load($id, "shopfinder_id");

        if(!$shopDataObject->getShopfinderId()) {
            throw new LocalizedException(__('Name must be set'));
        }
        if (isset($data['shop_name'])) {
            $shopDataObject->setShopName($data["shop_name"]);
        }
        if (isset($data['Country'])) {
            $shopDataObject->setCountry($data["Country"]);
        }
        if (isset($data['Image'])) {
            $shopDataObject->setImage($data["Image"]);
        }
        if (isset($data['Identifier'])) {
            $shopDataObject->setIdentifier($data["Identifier"]);
        }
        $this->shopFinderRepository->save($shopDataObject);
        $shopDataObject->setImage($mediaUrl.$shopDataObject->getImage());
        return $shopDataObject;
    }
}
