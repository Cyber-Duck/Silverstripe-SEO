<?php

class RobotsTxt_Controller extends Page_Controller 
{
    public function init()
    {
        parent::init(); 
        
        $this->response->addHeader('Content-Type','text/plain');
    }

    public function index(SS_HTTPRequest $request)
    {
        return $this->renderWith('Robots');
    }
}
