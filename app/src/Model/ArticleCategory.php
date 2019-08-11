<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;

class ArticleCategory extends DataObject
{
/* Lesson 10: Also take note that we won't use versioning for this DataObject. This is a deliberate decision based on
  the knowledge that there are no views where all of the categories will be listed. We know that the only way a
  category will appear on the frontend is when it is associated with an article. So based on that, we don't need
  to worry about the published state of categories.

    private static $extensions = [
        Versioned::class,
    ];It has been used in the region.php*/


    private static $db = [
        'Title' => 'Varchar',
    ];

    private static $has_one = [
        'ArticleHolder' => ArticleHolder::class,
    ];

    /*Lesson 10: Optional, but strongly recommended is a reciprocation of this relationship on the ArticleCategory object,
    using $belongs_many_many. This variable does not create any database mutations, but will provide an magic
    method to the object for getting its parent records. In this case, we know that we'll need any ArticleCategory
    object to get its articles, because our design includes a filter by category in the sidebar, so this is quite
    important. */

    private static $belongs_many_many = [
        'Articles' => ArticlePage::class,
    ];

    public function getCMSFields()
    {
        return FieldList::create(
            TextField::create('Title')
        );
    }
}