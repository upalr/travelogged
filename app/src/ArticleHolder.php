<?php

use SilverStripe\CMS\Model\SiteTree;

class ArticleHolder extends Page
{
    private static $allowed_children = [
        ArticlePage::class
    ];
}