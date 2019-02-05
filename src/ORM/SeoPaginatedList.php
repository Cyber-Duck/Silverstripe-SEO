<?php

namespace CyberDuck\SEO\ORM;

use SilverStripe\Control\Controller;
use SilverStripe\Control\HTTP;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\ORM\PaginatedList;

class SeoPaginatedList extends PaginatedList
{
    public function PrevLink()
    {
        if ($this->CurrentPage() == 2) {
            $request = $this->request instanceof HTTPRequest ? $this->request : Controller::curr()->getRequest();
            $url = $request->getURL(false);

            $sortingVar = $this->getPaginationGetVar();
            $getVars = $request->getVars();
            if (array_key_exists($sortingVar, $getVars)) {
                unset($getVars[$sortingVar]);
            }
            foreach ($getVars as $key => $value) {
                $url = HTTP::setGetVar($key, $value, $url);
            }
            return $url;
        }
        return parent::PrevLink();
    }
}
