<?php
namespace Chalhoub\Shopfinder\Controller\Adminhtml\Shop;

use Magento\Backend\App\Action\Context;
use Chalhoub\Shopfinder\Api\ShopRepositoryInterface as ShopRepository;
use Magento\Framework\Controller\Result\JsonFactory;
use Chalhoub\Shopfinder\Api\Data\ShopInterface;

class InlineEdit extends \Magento\Backend\App\Action
{
    /**
     * Authorization level of a basic admin session
     *
     * @see _isAllowed()
     */
    const ADMIN_RESOURCE = 'Chalhoub_Shopfinder::shop';

    /**
     * @var Chalhoub\Shopfinder\Api\ShopRepositoryInterface
     */
    protected $shopRepository;

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $jsonFactory;

    /**
     * @param Context $context
     * @param ShopRepository $shopRepository
     * @param JsonFactory $jsonFactory
     */
    public function __construct(
        Context $context,
        ShopRepository $shopRepository,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);
        $this->shopRepository = $shopRepository;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Json $resultJson */
        $resultJson = $this->jsonFactory->create();
        $error = false;
        $messages = [];

        if ($this->getRequest()->getParam('isAjax')) {
            $postItems = $this->getRequest()->getParam('items', []);
            if (!count($postItems)) {
                $messages[] = __('Please correct the data sent.');
                $error = true;
            } else {
                foreach (array_keys($postItems) as $shopId) {
                    /** @var \Chalhoub\Shopfinder\Model\Shop $shop*/
                    $shop= $this->shopRepository->getById($shopId);
                    try {
                        $shop->setData(array_merge($shop->getData(), $postItems[$shopId]));
                        $this->shopRepository->save($shop);
                    } catch (\Exception $e) {
                        $messages[] = $this->getErrorWithShopId(
                            $shop,
                            __($e->getMessage())
                        );
                        $error = true;
                    }
                }
            }
        }

        return $resultJson->setData([
            'messages' => $messages,
            'error' => $error
        ]);
    }

    /**
     * Add shop title to error message
     *
     * @param ShopInterface $shop
     * @param string $errorText
     * @return string
     */
    protected function getErrorWithShopId(ShopInterface $shop, $errorText)
    {
        return '[Shop ID: ' . $shop->getId() . '] ' . $errorText;
    }
}
