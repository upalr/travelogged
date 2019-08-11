<?php

use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\TextField;
use SilverStripe\Assets\Image;
use SilverStripe\Assets\File;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Forms\CheckboxSetField;


class ArticlePage extends Page
{
    private static $can_be_root = false;

    private static $db = [
        'Date' => 'Date',
        'Teaser' => 'Text',
        'AuthorName' => 'Varchar' /* DevC: we can declare this as a Varchar, which by default is limited to 100 characters.*/
    ];

    private static $has_one = [
        'Photo' => Image::class,
        'Brochure' => File::class
    ];

    private static $owns = [
        'Photo',
        'Brochure',
    ];

    private static $has_many = [
        'Comments' => ArticleComment::class,
    ];

    private static $many_many = [
        'Categories' => ArticleCategory::class,
    ];

    public function getCMSFields()
    {
        $fields = parent::getCMSFields(); // Retrieving the page's CMS Fields. For dataobject see Model/Region
        $fields->addFieldToTab('Root.Main', DateField::create('Date', 'Date of article'), 'Content');
        $fields->addFieldToTab('Root.Main', TextareaField::create('Teaser')
            ->setDescription('This is the summary that appears on the article list page.'),
            'Content'
        );
        $fields->addFieldToTab('Root.Main', TextField::create('AuthorName', 'Author of article'), 'Content');

        // Upload fields
        $fields->addFieldToTab('Root.Attachments', $photo = UploadField::create('Photo'));
        //$fields->addFieldToTab('Root.Attachments', UploadField::create('Brochure','Travel brochure, optional (PDF only)'));
        $fields->addFieldToTab('Root.Attachments', $brochure = UploadField::create(
            'Brochure',
            'Travel brochure, optional (PDF only)'
        ));
        /*Lesson 7: Notice that we can use the shortcut of concurrently adding the field to the tab, and assigning it to a variable.
         This technique is often used when making updates to form fields after instantiation.*/
        $photo->setFolderName('travel-photos');
        $brochure
            ->setFolderName('travel-brochures')
            ->getValidator()->setAllowedExtensions(array('pdf'));

        $fields->addFieldToTab('Root.Categories', CheckboxSetField::create(
            'Categories', //The name of the $many_many relation we're managing.
            'Selected categories',
            $this->Parent()->Categories()->map('ID', 'Title') // Parent means ArticleHolder
        ));

        return $fields;
    }

    public function CategoriesList()
    {
        if ($this->Categories()->exists()) { //We check the existence of categories with the exists() method. Simply checking the result of Categories() will not work, because it will at worst return an empty DataList object. It will never return false. We use exists() to check truthiness.
            return implode(', ', $this->Categories()->column('Title'));
        }

        return null;
    }

}