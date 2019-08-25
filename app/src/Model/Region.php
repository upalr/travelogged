<?php

use SilverStripe\ORM\DataObject;
use SilverStripe\Assets\Image;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\AssetAdmin\Forms\UploadField;
use SilverStripe\Versioned\Versioned;
use SilverStripe\Control\Controller;


class Region extends DataObject
{

    private static $db = [
        'Title' => 'Varchar',
        'Description' => 'HTMLText',
    ];

    private static $has_one = [
        'Photo' => Image::class,
        'RegionsPage' => RegionsPage::class, // Reciprocate of has_one
    ];

    private static $owns = [
        'Photo',
    ];

    private static $extensions = [
        Versioned::class,
    ];

    private static $summary_fields = [
        'Photo.Filename' => 'Photo file name',
        'GridThumbnail' => '',
        'Title' => 'Title',
        'Description' => 'Description'
    ];

    private static $versioned_gridfield_extensions = true;


    public function getGridThumbnail()
    {
        if ($this->Photo()->exists()) {
            return $this->Photo()->ScaleWidth(100);
        }

        return "(no image)";
    }

    //

    public function getCMSFields()
    {
        /*Lesson 9: You might have noticed that our getCMSFields() function looks a bit different. That's because
          we're not going to be using the typical page editing interface for this object, so we're not going
          to have the tabs that come with Page objects. We could very easily create one, but since this
          data type is so simple, we'll just leave it as a simple field list, and add all the form fields to the
          constructor.*/

        $fields = FieldList::create(  // This one os for CMS Field for dataobject, CMS filed for Page is on AtriclePage.php.
            TextField::create('Title'),
            HtmlEditorField::create('Description'),
            $uploader = UploadField::create('Photo')
        );

        $uploader->setFolderName('region-photos');
        $uploader->getValidator()->setAllowedExtensions(['png', 'gif', 'jpeg', 'jpg']);

        return $fields;
    }

    public function Link()
    {
        return $this->RegionsPage()->Link('show/' . $this->ID);
    }

    public function LinkingMode()
    {
        return Controller::curr()->getRequest()->param('ID') == $this->ID ? 'current' : 'link';
    }
}