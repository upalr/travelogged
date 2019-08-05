<?php

//use PageController;
use SilverStripe\CMS\Controllers\ContentController;
class HomePageController extends PageController
{
    public function LatestArticles($count = 3)
    {
        return ArticlePage::get()
            ->sort('Created', 'DESC')
            ->limit($count);
    }
}