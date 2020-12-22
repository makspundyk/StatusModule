<?php

namespace IdeaInYou\CustomerStatus\Controller\Account;

use IdeaInYou\CustomerStatus\Helper\GetCustomerStatus;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\View\Result\PageFactory;

class Status extends Action
{
    protected $_pageFactory;
    protected $customerHelper;

    protected $resultJsonFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        JsonFactory $jsonFactory,
        GetCustomerStatus $customerHelper
    ) {
        parent::__construct($context);
        $this->_pageFactory = $pageFactory;
        $this->resultJsonFactory = $jsonFactory;
        $this->customerHelper = $customerHelper;
    }

    public function execute()
    {
        $resultPage = $this->_pageFactory->create();
        $status = $this->getRequest()->getParam('status');

        if ($status) {
            try {

                $this->customerHelper->setCustomerStatus($status);
                $resultJson = $this->resultJsonFactory->create();

                return $resultJson->setData([
                    'success' => true,
                    'label' => $this->customerHelper->getCustomerStatus()
                ]);
            } catch (\Exception $e) {
                $this->messageManager->addErrorMessage(__('There was error while saving status'));
            }
        }

        return $resultPage;
    }
}
