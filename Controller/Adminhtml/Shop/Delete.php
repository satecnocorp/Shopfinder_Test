<?php
namespace Chalhoub\Shopfinder\Controller\Adminhtml\Shop;

use Magento\Framework\App\Action\HttpPostActionInterface;

class Delete extends \Chalhoub\Shopfinder\Controller\Adminhtml\Shop implements HttpPostActionInterface
{
    /**
     * Delete action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        // check if we know what should be deleted
        $id = $this->getRequest()->getParam('shop_id');
        if ($id) {
            try {
                // init model and delete
                $model = $this->_objectManager->create(\Chalhoub\Shopfinder\Model\Shop::class);
                $model->load($id);
                $model->delete();
                // display success message
                $this->messageManager->addSuccessMessage(__('You deleted the shop.'));
                // go to grid
                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                // display error message
                $this->messageManager->addErrorMessage($e->getMessage());
                // go back to edit form
                return $resultRedirect->setPath('*/*/edit', ['shop_id' => $id]);
            }
        }
        // display error message
        $this->messageManager->addErrorMessage(__('We can\'t find a shop to delete.'));
        // go to grid
        return $resultRedirect->setPath('*/*/');
    }
}
