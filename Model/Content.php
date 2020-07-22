<?php
/**
 * 
 */
namespace Apriljune\Testimonial\Model;

use Magento\Framework\Model\AbstractModel;

class Content extends \Magento\Framework\Model\AbstractModel {

    protected function _construct() {
        $this->_init('Apriljune\Testimonial\Model\ResourceModel\Content');
    }
}