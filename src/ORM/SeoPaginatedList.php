<?php

namespace CyberDuck\SEO\ORM;

use SilverStripe\Control\HTTP;
use SilverStripe\ORM\PaginatedList;

class SeoPaginatedList extends PaginatedList 
{
    public function PrevLink()
    {
        if($this->CurrentPage() == 2) {
            $url = $this->request->getURL(false);

            $sortingVar = $this->getPaginationGetVar();
            $getVars = $this->request->getVars();
            if(array_key_exists($sortingVar, $getVars)) {
                unset($getVars[$sortingVar]);
            }
            foreach($getVars as $key => $value) {
                $url = HTTP::setGetVar($key, $value, $url);
            }
            return $url;
        }
        return parent::PrevLink();
    }
}