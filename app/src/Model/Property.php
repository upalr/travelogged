<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\DropdownField;
use SilverStripe\Forms\CurrencyField;
use SilverStripe\Forms\DateField;
use SilverStripe\Forms\CheckboxField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\ORM\ArrayLib;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\TabSet;
use SilverStripe\Versioned\Versioned;


class Property extends DataObject
{

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'Text',
        'PricePerNight' => 'Currency',
        'Bedrooms' => 'Int',
        'Bathrooms' => 'Int',
        'FeaturedOnHomepage' => 'Boolean',
        'AvailableStart' => 'Date',
        'AvailableEnd'=> 'Date'
    ];

    private static $has_one = [
        'Region' => Region::class,
        'PrimaryPhoto' => Image::class,
    ];

    private static $summary_fields = [
        'Title' => 'Title',
        'Region.Title' => 'Region', //Lesson 13: Region is a has_one, so getting the RegionID is useless. We'll instead get the region's title, which is much more friendly. Region.Title translates to $this->Region()->Title
        'PricePerNight.Nice' => 'Price',
        'FeaturedOnHomepage.Nice' => 'Featured?'
    ];

    private static $owns = [
        'PrimaryPhoto',
    ];

    private static $extensions = [
        Versioned::class,
    ];

    private static $versioned_gridfield_extensions = true;

    /* private static $searchable_fields = [ //Lesson 13: By default, the DataObject will provide the same fields that are specified in $summary_fields
        'Title',
        'Region.Title',
        'FeaturedOnHomepage'
    ];*/


    // Lesson 13: In order to do that, we'll have to write some executable code,
    // which can't be placed in a static variable assignment, so let's change private static
    // $searchable_fields to public function searchableFields(),
    public function searchableFields()
    {
        return [
            'Title' => [
                'filter' => 'PartialMatchFilter',
                'title' => 'Title',
                'field' => TextField::class,
            ],
            'RegionID' => [
                'filter' => 'ExactMatchFilter',
                'title' => 'Region',
                'field' => DropdownField::create('RegionID')
                    ->setSource(
                        Region::get()->map('ID', 'Title')
                    )
                    ->setEmptyString('-- Any region --')
            ],
            'FeaturedOnHomepage' => [
                'filter' => 'ExactMatchFilter',
                'title' => 'Only featured'
            ]
        ];
    }

    public function getCMSfields()
    {
        $fields = FieldList::create(TabSet::create('Root'));
        $fields->addFieldsToTab('Root.Main', [
            TextField::create('Title'),
            TextareaField::create('Description'),
            CurrencyField::create('PricePerNight', 'Price (per night)'),
            DropdownField::create('Bedrooms')
                ->setSource(ArrayLib::valuekey(range(1, 10))),
            DropdownField::create('Bathrooms')
                ->setSource(ArrayLib::valuekey(range(1, 10))),
            DropdownField::create('RegionID', 'Region')
                ->setSource(Region::get()->map('ID', 'Title')),
            CheckboxField::create('FeaturedOnHomepage', 'Feature on homepage'),
            DateField::create('AvailableStart', 'Date available (start)'),
            DateField::create('AvailableEnd', 'Date available (end)'),
        ]);
        $fields->addFieldToTab('Root.Photos', $upload = UploadField::create(
            'PrimaryPhoto',
            'Primary photo'
        ));

        $upload->getValidator()->setAllowedExtensions(array(
            'png', 'jpeg', 'jpg', 'gif'
        ));
        $upload->setFolderName('property-photos');

        return $fields;
    }
}