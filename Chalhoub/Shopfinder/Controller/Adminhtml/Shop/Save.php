<?php
namespace Chalhoub\Shopfinder\Controller\Adminhtml\Shop;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Backend\App\Action\Context;
use Chalhoub\Shopfinder\Api\ShopRepositoryInterface;
use Chalhoub\Shopfinder\Model\Shop;
use Chalhoub\Shopfinder\Model\ShopFactory;
use Chalhoub\Shopfinder\Model\ImageUploader;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Registry;

/**
 * Save Shop action.
 */
class Save extends \Chalhoub\Shopfinder\Controller\Adminhtml\Shop implements HttpPostActionInterface
{
    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var ShopFactory
     */
    private $shopFactory;

    /**
     * @var ShopRepositoryInterface
     */
    private $shopRepository;

    /**
     * @param Context $context
     * @param Registry $coreRegistry
     * @param DataPersistorInterface $dataPersistor
     * @param ShopFactory|null $shopFactory
     * @param ShopRepositoryInterface|null $shopRepository
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry,
        ImageUploader $imageUploaderModel,
        DataPersistorInterface $dataPersistor,
        ShopFactory $shopFactory = null,
        ShopRepositoryInterface $shopRepository = null
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->shopFactory = $shopFactory
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(ShopFactory::class);
        $this->shopRepository = $shopRepository
            ?: \Magento\Framework\App\ObjectManager::getInstance()->get(ShopRepositoryInterface::class);
        parent::__construct($context, $coreRegistry);
        $this->imageUploaderModel = $imageUploaderModel;
    }

    /**
     * Save action
     *
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            /** @var \Chalhoub\Shopfinder\Model\Shop $model */
            $model = $this->shopFactory->create();

            $id = $this->getRequest()->getParam('shop_id');
            if ($id) {
                try {
                    $model = $this->shopRepository->getById($id);
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage(__('This Shop no longer exists.'));
                    return $resultRedirect->setPath('*/*/');
                }
            }
            $model->setData($data);
            $model = $this->imageData($model, $data);
             //TODO die(print_r($model->getImage(),true));
            try {
                $this->shopRepository->save($model);
                $this->messageManager->addSuccessMessage(__('You saved the shop.'));
                $this->dataPersistor->clear('shopfinder_shop');
                return $this->processShopReturn($model, $data, $resultRedirect);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the shop.'));
            }

            $this->dataPersistor->set('shopfinder_shop', $data);
            return $resultRedirect->setPath('*/*/edit', ['shop_id' => $id]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    /**
     * Process and set the shop return
     *
     * @param \Chalhoub\Shopfinder\Model\Shop $model
     * @param array $data
     * @param \Magento\Framework\Controller\ResultInterface $resultRedirect
     * @return \Magento\Framework\Controller\ResultInterface
     */
    private function processShopReturn($model, $data, $resultRedirect)
    {
        $redirect = $data['back'] ?? 'close';

        if ($redirect ==='continue') {
            $resultRedirect->setPath('*/*/edit', ['shop_id' => $model->getId()]);
        } elseif ($redirect === 'close') {
            $resultRedirect->setPath('*/*/');
        } elseif ($redirect === 'duplicate') {
            $duplicateModel = $this->shopFactory->create(['data' => $data]);
            $duplicateModel->setId(null);
            $duplicateModel->setIdentifier($data['identifier'] . '-' . uniqid());
            $duplicateModel->setIsActive(Shop::STATUS_DISABLED);
            $this->shopRepository->save($duplicateModel);
            $id = $duplicateModel->getId();
            $this->messageManager->addSuccessMessage(__('You duplicated the shop.'));
            $this->dataPersistor->set('shopfinder_shop', $data);
            $resultRedirect->setPath('*/*/edit', ['shop_id' => $id]);
        }
        return $resultRedirect;
    }

    /**
     * @param $model
     * @param $data
     * @return mixed
     */
    public function imageData($model, $data)
    {
        if ($model->getId()) {
            $pageData = $this->shopFactory->create();
            $pageData->load($model->getId());
            if (isset($data['image'][0]['name'])) {
                $imageName1 = $pageData->getThumbnail();
                $imageName2 = $data['image'][0]['name'];
                if ($imageName1 != $imageName2) {
                    $imageUrl = $data['image'][0]['url'];
                    $imageName = $data['image'][0]['name'];
                    $data['image'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
                } else {
                    $data['image'] = $data['image'][0]['name'];
                }
            } else {
                $data['image'] = '';
            }
        } else {
            if (isset($data['image'][0]['name'])) {
                $imageUrl = $data['image'][0]['url'];
                $imageName = $data['image'][0]['name'];
                $data['image'] = $this->imageUploaderModel->saveMediaImage($imageName, $imageUrl);
            }
        }
        $model->setData($data);
        return $model;
    }
}
