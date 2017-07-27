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
        $start = (int) $this->owner->request->getPaginationGetVar();

        if($start === 0) {
            return $this->owner->httpError(404);
        }
        if($list->CurrentPage() > $list->TotalPages()){
            return $this->owner->httpError(404);
        }
        if($start % $list->getPageLength() !== 0){
            return $this->owner->httpError(404);
        }
        if(!preg_match('/^[0-9]+$/', $start)){
            return $this->owner->httpError(404);
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
            if($this->pagination->TotalPages() > 1) {
                if($this->pagination->CurrentPage() === 2) {
                    return $this->getPageURL();
                } else {
                    $start = $this->pagination->getPageStart() - $this->pagination->getPageLength();

                    return $this->getPageURL().'?'.$this->pagination->getPaginationGetVar().'='.$start;
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

                return $this->getPageURL().'?'.$this->pagination->getPaginationGetVar().'='.$start;
            }
        }
    }
}