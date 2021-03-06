<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Apriljune\Testimonial\Model\ResourceModel\Content;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Apriljune\Testimonial\Model\Content as Model;
use Apriljune\Testimonial\Model\ResourceModel\Content as ResourceModel;

class Collection extends AbstractCollection{
	
    protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}