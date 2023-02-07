<?php

namespace Chalhoub\Shopfinder\Model;

use Chalhoub\Shopfinder\Api\ShopRepositoryInterface;
use Chalhoub\Shopfinder\Api\Data;
use Chalhoub\Shopfinder\Model\ResourceModel\Shop as ResourceShop;
use Chalhoub\Shopfinder\Model\ResourceModel\Shop\CollectionFactory as ShopCollectionFactory;
use Magento\Framework\Api\DataObjectHelper;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Reflection\DataObjectProcessor;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\EntityManager\HydratorInterface;

/**
 * Default shop repo impl.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class ShopRepository implements ShopRepositoryInterface
{
    /**
     * @var ResourceShop
     */
    protected $resource;

    /**
     * @var ShopFactory
     */
    protected $ShopFactory;

    /**
     * @var ShopCollectionFactory
     */
    protected $shopCollectionFactory;

    /**
     * @var Data\ShopSearchResultsInterfaceFactory
     */
    protected $searchResultsFactory;

    /**
     * @var DataObjectHelper
     */
    protected $dataObjectHelper;

    /**
     * @var DataObjectProcessor
     */
    protected $dataObjectProcessor;

    /**
     * @var \Chalhoub\Shopfinder\Api\Data\ShopInterfaceFactory
     */
    protected $dataShopFactory;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;

    /**
     * @var HydratorInterface
     */
    private $hydrator;

    /**
     * @param ResourceShop $resource
     * @param ShopFactory $shopFactory
     * @param Data\ShopInterfaceFactory $dataShopFactory
     * @param ShopCollectionFactory $shopCollectionFactory
     * @param Data\ShopSearchResultsInterfaceFactory $searchResultsFactory
     * @param DataObjectHelper $dataObjectHelper
     * @param DataObjectProcessor $dataObjectProcessor
     * @param StoreManagerInterface $storeManager
     * @param CollectionProcessorInterface $collectionProcessor
     * @param HydratorInterface|null $hydrator
     *
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        ResourceShop $resource,
        ShopFactory $shopFactory,
        \Chalhoub\Shopfinder\Api\Data\ShopInterfaceFactory $dataShopFactory,
        ShopCollectionFactory $shopCollectionFactory,
        Data\ShopSearchResultsInterfaceFactory $searchResultsFactory,
        DataObjectHelper $dataObjectHelper,
        DataObjectProcessor $dataObjectProcessor,
        StoreManagerInterface $storeManager,
        CollectionProcessorInterface $collectionProcessor = null,
        ?HydratorInterface $hydrator = null
    ) {
        $this->resource = $resource;
        $this->shopFactory = $shopFactory;
        $this->shopCollectionFactory = $shopCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->dataObjectHelper = $dataObjectHelper;
        $this->dataShopFactory = $dataShopFactory;
        $this->dataObjectProcessor = $dataObjectProcessor;
        $this->storeManager = $storeManager;
        $this->collectionProcessor = $collectionProcessor ?: $this->getCollectionProcessor();
        $this->hydrator = $hydrator ?? ObjectManager::getInstance()->get(HydratorInterface::class);
    }

    /**
     * Save Shop data
     *
     * @param \Chalhoub\Shopfinder\Api\Data\ShopInterface $shop
     * @return Shop
     * @throws CouldNotSaveException
     */
    public function save(Data\ShopInterface $shop)
    {
   
       /* if ($shop->getId() && $shop instanceof Shop && !$shop->getOrigData()) {
            $shop = $this->hydrator->hydrate($this->getById($shop->getId()), $this->hydrator->extract($shop));
        }*/

        try {
            $this->resource->save($shop);
        } catch (\Exception $exception) {
            throw new CouldNotSaveException(__($exception->getMessage()));
        }
        return $shop;
    }

    /**
     * Load Shop data by given Shop Identity
     *
     * @param string $shopId
     * @return Shop
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($shopId)
    {
        $shop = $this->shopFactory->create();
        $this->resource->load($shop, $shopId);
        if (!$shop->getId()) {
            throw new NoSuchEntityException(__('The Shop with the "%1" ID doesn\'t exist.', $shopId));
        }
        return $shop;
    }
        /**
     * Load Shop data by given Shop Identifier
     *
     * @param string $shopIdentifier
     * @return Shop
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getByIdentifier($shopIdentifier)
    {
        $shop = $this->shopFactory->create();
        $this->resource->load($shop, $shopIdentifier,'identifier');
        if (!$shop->getId()) {
            throw new NoSuchEntityException(__('The Shop with the Identifier "%1"  doesn\'t exist.', $shopIdentifier));
        }
        return $shop;
    }

    /**
     * Load Shop data collection by given search criteria
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     * @param \Magento\Framework\Api\SearchCriteriaInterface $criteria
     * @return \Chalhoub\Shopfinder\Api\Data\ShopSearchResultsInterface
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $criteria)
    {
        /** @var \Chalhoub\Shopfinder\Model\ResourceModel\Shop\Collection $collection */
        $collection = $this->shopCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        /** @var Data\ShopSearchResultsInterface $searchResults */
        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);
        $searchResults->setItems($collection->getItems());
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * Delete Shop
     *
     * @param \Chalhoub\Shopfinder\Api\Data\ShopInterface $shop
     * @return bool
     * @throws CouldNotDeleteException
     */
    public function delete(Data\ShopInterface $shop)
    {
        try {
            $this->resource->delete($shop);
        } catch (\Exception $exception) {
            throw new CouldNotDeleteException(__($exception->getMessage()));
        }
        return true;
    }

    /**
     * Delete Shop by given Shop Identity
     *
     * @param string $shopId
     * @return bool
     * @throws CouldNotDeleteException
     * @throws NoSuchEntityException
     */
    public function deleteById($shopId)
    {
        return $this->delete($this->getById($shopId));
    }

    /**
     * Retrieve collection processor
     *
     * @deprecated 102.0.0
     * @return CollectionProcessorInterface
     */
    private function getCollectionProcessor()
    {
        //phpcs:disable Magento2.PHP.LiteralNamespaces
        if (!$this->collectionProcessor) {
            $this->collectionProcessor = \Magento\Framework\App\ObjectManager::getInstance()->get(
                'Chalhoub\Shopfinder\Model\Api\SearchCriteria\ShopCollectionProcessor'
            );
        }
        return $this->collectionProcessor;
    }
}
