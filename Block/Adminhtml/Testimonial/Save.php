<?php

namespace Apriljune\Testimonial\Block\Adminhtml\Testimonial;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Catalog\Api\CategoryRepositoryInterface;
use Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory;
use Apriljune\Testimonial\Model\Testimonial;
use Apriljune\Testimonial\Model\ResourceModel\Testimonial\Collection;

/**
* Class Save
*
* @package Apriljune\Testimonial\Block\Adminhtml\Testimonial
*/
class Save extends Action
{
   /**
    * @var CategoryRepositoryInterface
    */
   protected $categoryRepository;
   /**
    * @var \Magento\Framework\App\Request\DataPersistorInterface
    */
   protected $dataPersistor;
   /**
    * @var \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory
    */
   protected $attributeFactory;

   /**
    * @param \Magento\Backend\App\Action\Context                       $context
    * @param \Magento\Framework\App\Request\DataPersistorInterface     $dataPersistor
    * @param \Magento\Catalog\Model\ResourceModel\Eav\AttributeFactory $attributeFactory
    * @param \Magento\Catalog\Api\CategoryRepositoryInterface          $categoryRepository
    */
   public function __construct(
       Context $context,
       DataPersistorInterface $dataPersistor,
       AttributeFactory $attributeFactory,
       CategoryRepositoryInterface $categoryRepository
   ) {
       $this->dataPersistor = $dataPersistor;
       $this->attributeFactory = $attributeFactory;
       $this->categoryRepository = $categoryRepository;
       parent::__construct($context);
   }

   /**
    * Save action
    *
    * @return \Magento\Framework\Controller\ResultInterface
    * @throws \Magento\Framework\Exception\LocalizedException
    */
   public function execute()
   {
       /**
        * @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect
        */
       $resultRedirect = $this->resultRedirectFactory->create();
       $data = $this->getRequest()->getPostValue();
       if ($data) {
           $id = $this->getRequest()->getParam('id');
      
           $model = $this->_objectManager->create(Testimonial::class)->load($id);
           if (!$model->getId() && $id) {
               $this->messageManager->addErrorMessage(__('This Testimonial no longer exists.'));
               return $resultRedirect->setPath('*/*/');
           }

           try {
               $model->save();
               $this->messageManager->addSuccessMessage(__('You saved the Brand.'));
               $this->dataPersistor->clear('syncit_brand_brand');
      
               if ($this->getRequest()->getParam('back')) {
                   return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
               }
               return $resultRedirect->setPath('*/*/');
           } catch (LocalizedException $e) {
               $this->messageManager->addErrorMessage($e->getMessage());
           } catch (\Exception $e) {
               $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the Brand.'));
           }
      
           $this->dataPersistor->set('syncit_brand_brand', $data);
           return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
       }
       return $resultRedirect->setPath('*/*/');
   }
}