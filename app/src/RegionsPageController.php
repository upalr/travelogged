<?php

//use PageController;

use SilverStripe\CMS\Controllers\ContentController;
use SilverStripe\Control\HTTPRequest;


class RegionsPageController extends PageController
{
    private static $allowed_actions = [
        'show'
    ];

    public function show(HTTPRequest $request)
    {
        $region = Region::get()->byID($request->param('ID'));

        if(!$region) {  // Validation
            return $this->httpError(404,'That region could not be found');
        }

        return [
            'Region' => $region,
            'Title' => $region->Title
        ];
    }

}