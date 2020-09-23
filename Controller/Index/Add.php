<?php
/**
 *
 * @package     Testimonial
 * @author      Shoaib Fareed
 * @license     https://opensource.org/licenses/OSL-3.0 Open Software License v. 3.0 (OSL-3.0)
 * @link        https://apriljune.com
 */

namespace Apriljune\Testimonial\Controller\Index;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Apriljune\Testimonial\Model\Testimonial;
use Apriljune\Testimonial\Model\Author;
use Apriljune\Testimonial\Model\Social;
use Apriljune\Testimonial\Model\Content;
use Apriljune\Testimonial\Model\ResourceModel\Testimonial as TestimonialResource;
use Apriljune\Testimonial\Model\ResourceModel\Author as AuthorResource;
use Apriljune\Testimonial\Model\ResourceModel\Social as SocialResource;
use Apriljune\Testimonial\Model\ResourceModel\Content as ContentResource;
use Magento\Framework\Controller\ResultFactory; 

class Add extends Action
{
    /**
     * @var Testimonial
     */
    private $testimonial;

    /**
     * @var Social
     */
    private $social;

    /**
     * @var Content
     */
    private $content;

    /**
     * @var Author
     */
    private $author;

    /**
     * @var TestimonialResource
    */
    private $testimonialResource;

    /**
     * @var SocialResource
    */
    private $socialResource;

    /**
     * @var ContentResource
    */
    private $contentResource;

    /**
     * @var AuthorResource
    */
    private $authorResource;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    /**
     * Message manager interface
     *
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;


    /**
     * Add constructor.
     * @param Context $context
     * @param Testimonial $testimonial
     * @param Social $social
     * @param Author $author
     * @param Content $content
     * @param TestimonialResource $testimonialResource
     * @param SocialResource $socialResource
     * @param ContentResource $contentResource
     * @param AuthorResource $authorResource
     */

    public function __construct(

        Context $context,
        Testimonial $testimonial,
        Social $social,
        Author $author,
        Content $content,
        TestimonialResource $testimonialResource,
        SocialResource $socialResource,
        AuthorResource $authorResource,
        ContentResource $contentResource
    )
    {
        parent::__construct($context);
        $this->testimonial = $testimonial;
        $this->social      = $social;
        $this->content     = $content;
        $this->author      = $author;

        $this->testimonialResource = $testimonialResource;
        $this->socialResource      = $socialResource;
        $this->contentResource     = $contentResource;
        $this->authorResource      = $authorResource;
        $this->resultFactory = $context->getResultFactory();
        $this->messageManager = $context->getMessageManager();
    }

    /**
     * Execute action based on request and return result
     *
     * Note: Request will be added as operation argument in future
     *
     * @return \Magento\Framework\Controller\ResultInterface|ResponseInterface
     * @throws \Magento\Framework\Exception\NotFoundException
     */

    public function execute()
    {

        /* Get the post data */
        $data = (array) $this->getRequest()->getPost();

        if (!empty($data)) {

            /* Set the data in the model */
            $testimonialModel = $this->testimonial;
            $authorModel = $this->author;
            $contentModel = $this->content;
            $socialModel = $this->social;

            $date             = date('Y-m-d h:i:sa'); 

            // Set Parent Table Testimonial Data
            $testimonialModel->setData('status', 0 );
            $testimonialModel->setData('created_at', $date);
            $testimonialModel->setData('modified_at', $date);

            //Save Testimonial Table Data
            $this->testimonialResource->save($testimonialModel);

            //Get the last added Testimonial
            $lastTestimonialId = $testimonialModel->getId();

            //Set Testimonial Author Data
            $authorModel->setData('testimonial_id', $lastTestimonialId);
            $authorModel->setData('author_name', $data['author_name']);
            $authorModel->setData('author_email', $data['author_email']);
            $authorModel->setData('author_company', $data['author_company']);
            $authorModel->setData('author_job_title', $data['author_job_title']);
            $authorModel->setData('author_city', $data['author_city']);
            $authorModel->setData('author_image', '123456789');

            //Set Testimonial Social Data
            $socialModel->setData('testimonial_id', $lastTestimonialId);
            $socialModel->setData('facebook_url', $data['facebook_url']);
            $socialModel->setData('linkedin_url', $data['linkedin_url']);
            $socialModel->setData('twitter_url', $data['twitter_url']);
            $socialModel->setData('youtu_url', $data['youtu_url']);

            //Set Testimonial Content Data
            $contentModel->setData('testimonial_id', $lastTestimonialId);
            $contentModel->setData('testimonial_title', $data['testimonial_title']);
            $contentModel->setData('testimonial_description', $data['testimonial_description']);
            $contentModel->setData('testimonial_rating_number', $data['testimonial_rating_number']);

            //Save Testimonial Author Data
            $this->authorResource->save($authorModel);

            //Save Social Informaiton
            $this->socialResource->save($socialModel);

            //Save Testimonial Content Informaiton
            $this->contentResource->save($contentModel);

            $this->messageManager->addSuccessMessage("Testimonial saved successfully!");
                      
            /* Redirect back to Testimonial page */
            $redirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $redirect->setPath('/testimonial/index/add');
            return $redirect;
        }

        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}