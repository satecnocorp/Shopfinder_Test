<?php
/**
 * Copyright ©  All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Chalhoub\ShopFinder\Controller\Adminhtml\ShopFinder;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('shopfinder_id');

            if (isset($data['Image'][0]['name']) && isset($data['Image'][0]['tmp_name'])) {
                $data['Image'] =$data['Image'][0]['name'];
            }
            else
            {
                    $data['Image'] = null;
            }

            $model = $this->_objectManager->create(\Chalhoub\ShopFinder\Model\ShopFinder::class)->load($id);
            if (!$model->getId() && $id) {
                $this->messageManager->addErrorMessage(__('This Shopfinder no longer exists.'));
                return $resultRedirect->setPath('*/*/');
            }
            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/customsave.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('Array Log'.print_r($data, true));


            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(__('You saved the Shopfinder.'));
                $this->dataPersistor->clear('chalhoub_shopfinder_shopfinder');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath('*/*/edit', ['shopfinder_id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Shopfinder.'));
            }

            $this->dataPersistor->set('chalhoub_shopfinder_shopfinder', $data);
            return $resultRedirect->setPath('*/*/edit', ['shopfinder_id' => $this->getRequest()->getParam('shopfinder_id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}

