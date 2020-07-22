<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Apriljune\Testimonial\Model\ResourceModel\Testimonial;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use Apriljune\Testimonial\Model\Testimonial as Model;
use Apriljune\Testimonial\Model\ResourceModel\Testimonial as ResourceModel;

class Collection extends AbstractCollection {
	
	protected $_idFieldName = 'id';
	
	protected function _construct()
    {
        $this->_init(Model::class, ResourceModel::class);
    }
}