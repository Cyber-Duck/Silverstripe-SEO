<?php
/**
 * SEO_PublishPageRequest
 *
 * Adds the save button to the SEO admin CMS form
 *
 * @package silverstripe-seo
 * @license MIT License https://github.com/cyber-duck/silverstripe-seo/blob/master/LICENSE
 * @author  <andrewm@cyber-duck.co.uk>
 **/
class SEO_PublishPageRequest extends GridFieldDetailForm_ItemRequest
{
    /**
     * Allow requests to ItemEditForm
     *
     * @since version 1.0.0
     *
     * @config array $allowed_actions 
     **/
    private static $allowed_actions = ['ItemEditForm'];
    
    /**
     * Chechs the current object in SEO Admin and creats publish and draft buttons if
     * the current object is an instance of Page
     *
     * @since version 1.0.2
     *
     * @return object Returns a record FieldList
     **/
    public function ItemEditForm()
    {
        $form = parent::ItemEditForm();

        if($this->record instanceof Page){
            $actions = $form->Actions();

            $actions->removeByName('action_doSave');

            $button = FormAction::create('doDraft');
            $button->setTitle('Save as Draft');
            $button->addExtraClass('ss-ui-action ');
            $actions->push($button);

            $button = FormAction::create('doPublish');
            $button->setTitle('Save & Publish');
            $button->addExtraClass('ss-ui-action-constructive ui-button-text-icon-primary');
            $actions->push($button);

            $form->setActions($actions);
        }
        return $form;
    }
    
    /**
     * Set a page to draft status
     *
     * @since version 1.0.2
     *
     * @param array  $data Form data
     * @param object $form Form object instance
     *
     * @return void
     **/
    public function doDraft($data, $form)
    {
        $this->pageChange('draft', $data, $form);
    }
    
    /**
     * Set a page to published status
     *
     * @since version 1.0.2
     *
     * @param array  $data Form data
     * @param object $form Form object instance
     *
     * @return void
     **/
    public function doPublish($data, $form)
    {
        $this->pageChange('published', $data, $form, true);
    }
    
    /**
     * Sets a page status to either draft or published
     *
     * @since version 1.0.2
     *
     * @param string  $status  The page status
     * @param array   $data    Form data
     * @param object  $form    Form object instance
     * @param boolean $publish Set whether to publish the page
     *
     * @return void
     **/
    private function pageChange($status, $data, $form, $publish = false)
    {
        if($this->record->ID == NULL){
            $class = $this->record->ClassName;

            $page = new $class();

            $form->saveInto($page);
            $page->writeToStage('Stage');

            if($publish === true) $page->doPublish();

            $form->sessionMessage('Saved '.$status.' page', 'good');

            return $this->pageRedirect($page, $data);
        }
        $page = DataObject::get_by_id($this->record->ClassName, $this->record->ID);

        if($page == NULL){
            $page = Versioned::get_by_stage($this->record->ClassName, 'Stage')->byID($this->record->ID);
        }

        $form->saveInto($page);
        $page->write();
        $page->writeToStage('Stage');
        
        if($publish === true) {
            $page->doPublish();
        } else {
            $page->doUnpublish();
        }

        $form->sessionMessage('Updated '.$status.' page', 'good');

        Controller::curr()->redirectBack();
    }
    
    /**
     * Redirect after a page is published
     *
     * @since version 1.0.2
     *
     * @param object $page Page object
     * @param array  $data Page data
     *
     * @return void
     **/
    private function pageRedirect($page, $data)
    {
        $page->flushCache();

        $controller = Controller::curr();

        if($this->gridField->getList()->byId($this->record->ID)){
            return $this->edit(Controller::curr()->getRequest());
        } else {
            $noActionURL = $controller->removeAction($data['url']);
            $controller->getRequest()->addHeader('X-Pjax', 'Content');

            return $controller->redirect($noActionURL, 302);
        }
    }
}