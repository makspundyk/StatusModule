<?php

namespace IdeaInYou\CustomerStatus\Helper;

use Magento\Customer\Api\CustomerRepositoryInterfaceFactory;
use Magento\Customer\Model\ResourceModel\Customer\CollectionFactory;
use Magento\Customer\Model\ResourceModel\CustomerFactory;
use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\View\Result\PageFactory;

class  GetCustomerStatus extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $customerSession;

    protected $customerCollectionFactory;

    protected $customerRepositoryFactory;

    protected $customerFactory;

    public function __construct(
        Context $context,
        PageFactory $pageFactory,
        CustomerSession $customerSession,
        CollectionFactory $customerCollectionFactory,
        CustomerFactory $customerFactory,
        CustomerRepositoryInterfaceFactory $customerRepositoryFactory,
        JsonFactory $jsonFactory
    ) {
        $this->_pageFactory = $pageFactory;
        $this->customerSession = $customerSession;
        $this->customerCollectionFactory = $customerCollectionFactory;
        $this->customerFactory = $customerFactory;
        $this->resultJsonFactory = $jsonFactory;
        $this->customerRepositoryFactory = $customerRepositoryFactory;
        parent::__construct($context);
    }

    public function setCustomerStatus($status)
    {
        $customerRepository  = $this->customerRepositoryFactory->create();
        try {
            $newCustomer = $customerRepository->getById($this->customerSession->getCustomer()->getId());
            if ($status) {
                $newCustomer->setCustomAttribute('customer_status', $status);
                $customerRepository->save($newCustomer);
            }
        } catch (NoSuchEntityException | LocalizedException $e) {
            $this->_logger->notice('blabla');
        }
    }

    public function getCustomerStatus()
    {
        $customerCollection = $this->customerCollectionFactory->create()->addAttributeToSelect('customer_status');
        $customerNew = $customerCollection->getItemById($this->customerSession->getCustomer()->getId());
        $customerData = $customerNew->getDataModel();
        if ($customerData->getCustomAttribute('customer_status') !== null) {
            return $customerData->getCustomAttribute('customer_status')->getValue();
        }

        return false;
    }
}
