<?php

namespace Apriljune\Testimonial\Model;

use Apriljune\Testimonial\Model\ResourceModel\Testimonial\CollectionFactory;

class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $loadedData;
    protected $storeManager;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        CollectionFactory $TestimonialCollection,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $TestimonialCollection->create();
        $this->_storeManager = $storeManager;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $items = $this->collection->getItems();
        foreach ($items as $job_data) {
            $this->loadedData[$job_data->getId()] = $job_data->getData();
        }
        return $this->loadedData;
    }
}