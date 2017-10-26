<?php
/**
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 *
 * SEO_PageControllerExtension
 */
class SEO_PageControllerExtension extends Extension
{
    /**
     * A PaginatedList instance used for rel Meta tags
     *
     * @since version 2.0.0
     *
     * @var PaginatedList $pagination 
     **/
    private $pagination;

    /**
     * A DataObject instance to pull the current page SEO properties from
     *
     * @since version 2.0.0
     *
     * @var DataObject $seo 
     **/
    private $seo;

    /**
     * Sets a Paginated list object which the prev and next rel tags will be 
     * calculated off. This method validates the current $_GET param used for 
     * pagination and will return a 404 response if the $_GET var is outside
     * of the expected range. e.g start=100 but only 99 items in the list
     *
     * @since version 2.0.0
     *
     * @param PaginatedList $list   Paginated list object
     * @param array         $params Array of $_GET params to allow in the URL // todo
     *
     * @return string|404 response
     **/
    public function setPaginationTags(PaginatedList $list, $params = [])
    {
        if($this->owner->request->getVar($list->getPaginationGetVar()) !== NULL) {
            if((int) $list->getPageStart() === 0) {
                //return $this->owner->httpError(404); // todo
            }
            if($list->CurrentPage() > $list->TotalPages()){
                return $this->owner->httpError(404);
            }
            if($list->getPageStart() % $list->getPageLength() !== 0){
                return $this->owner->httpError(404);
            }
            if(!preg_match('/^[0-9]+$/', $list->getPageStart())){
                return $this->owner->httpError(404);
            }
        }
        $this->pagination = $list;
    }

    /**
     * Get the current page prev pagination link
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPaginationPrevTag()
    {
        if($this->pagination) {
            if($this->pagination->TotalPages() > 1 && $this->pagination->NotFirstPage()) {
                if((int) $this->pagination->CurrentPage() === 2) {
                    return $this->owner->getPageURL();
                } else {
                    $start = $this->pagination->getPageStart() - $this->pagination->getPageLength();

                    return $this->owner->getPageURL().'?'.$this->pagination->getPaginationGetVar().'='.$start;
                }
            }
        }
    }

    /**
     * Get the current page next pagination link
     *
     * @since version 2.0.0
     *
     * @return string
     **/
    public function getPaginationNextTag()
    {
        if($this->pagination) {
            if($this->pagination->TotalPages() > 1 && $this->pagination->NotLastPage()) {
                $start = $this->pagination->getPageStart() + $this->pagination->getPageLength();

                return $this->owner->getPageURL().'?'.$this->pagination->getPaginationGetVar().'='.$start;
            }
        }
    }

    /**
     * Set the model to use for the current page Meta
     *
     * @since version 2.0.0
     *
     * @param DataObject $object
     *
     * @return void
     **/
    public function setSeoObject(DataObject $object)
    {
        $this->seo = $object;
    }

    /**
     * Return the head tags to use for the current page
     *
     * @since version 2.0.0
     *
     * @return ViewableData
     **/
    public function getSeoMetaTags()
    {
        return Controller::curr()->customise([
            'SEOPage' => $this->seo ? $this->seo : $this->owner
        ])->renderWith('HeadTags');
    }
}