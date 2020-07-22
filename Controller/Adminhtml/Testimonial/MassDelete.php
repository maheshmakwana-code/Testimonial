<?php

namespace Apriljune\Testimonial\Controller\Adminhtml\Testimonial;

use Exception;
use Magento\Backend\App\Action;
use Magento\Framework\Controller\ResultFactory;
use Magento\Backend\App\Action\Context;
use Apriljune\Testimonial\Model\ResourceModel\Testimonial\Collection as Testimonial;

/**
 * Class MassDelete
 *
 * @package Apriljune\Testimonial\Controller\Adminhtml\Testimonial
 */
class MassDelete extends Action
{

    /**
     * @var Testimonial
     */
    protected $testimonial;

    /**
     * Message manager interface
     *
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;


    /**
     * MassDelete constructor.
     * @param Context $context
     * @param Testimonial $testimonial
     */
    public function __construct( Context $context, Testimonial $testimonial )
    {
        parent::__construct($context);
        $this->testimonial = $testimonial;
        $this->messageManager = $context->getMessageManager();
        $this->resultFactory = $context->getResultFactory();
    }

    /**
     * Execute action
     *
     * @return \Magento\Backend\Model\View\Result\Redirect
     */
    public function execute()
    {
        $selectedIds = $this->getRequest()->getParams()['selected'];
        if (!is_array($selectedIds)) {
            $this->messageManager->addErrorMessage(__('Please select one or more testimonial.'));
        } else {
            try {
                $collectionSize = count($selectedIds);
                foreach ($selectedIds as $_id) {
                    $testimonial = $this->testimonial->getItems()[$_id];
                    $testimonial->delete();
                }
                $this->messageManager->addSuccessMessage(__('A total of %1 record(s) have been deleted.', $collectionSize));
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            }
        }

        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        return $resultRedirect->setPath('*/*/');
    }
}