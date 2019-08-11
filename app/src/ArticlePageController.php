<?php

//use PageController;
use SilverStripe\CMS\Controllers\ContentController;

use SilverStripe\Forms\Form;
use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\EmailField;
use SilverStripe\Forms\TextareaField;
use SilverStripe\Forms\FormAction;
use SilverStripe\Forms\RequiredFields;


class ArticlePageController extends PageController
{
    private static $allowed_actions = [
        'CommentForm',
    ];

    public function CommentForm()
    {
        $form = Form::create(
            $this,
            __FUNCTION__,
            FieldList::create(
                TextField::create('Name', '')
                    ->setAttribute('placeholder', 'Name*')
                    ->addExtraClass('form-control'),
                EmailField::create('Email', '')
                    ->setAttribute('placeholder', 'Email*')
                    ->addExtraClass('form-control'),
                TextareaField::create('Comment', '')
                    ->setAttribute('placeholder', 'Comment*')
                    ->addExtraClass('form-control')
            ),
            FieldList::create(
                FormAction::create('handleComment', 'Post Comment')
                    ->setUseButtonTag(true)
                    ->addExtraClass('btn btn-default-color btn-lg')
            ),
            RequiredFields::create('Name', 'Email', 'Comment')
        );

        $form->addExtraClass('form-style');

        //return $form;

        $data = $this->getRequest()->getSession()->get("FormData.{$form->getName()}.data");

        return $data ? $form->loadDataFrom($data) : $form; // send from with data if there is data.
    }

    //ANOTHER WAY OF CREATING COMMENTFORM // Dynamically adding attributes to the form fields
    /*public function CommentForm()
    {
        $form = Form::create(
            $this,
            __FUNCTION__,
            FieldList::create(
                TextField::create('Name',''),
                EmailField::create('Email',''),
                TextareaField::create('Comment','')
            ),
            FieldList::create(
                FormAction::create('handleComment','Post Comment')
                    ->setUseButtonTag(true)
                    ->addExtraClass('btn btn-default-color btn-lg')
            ),
            RequiredFields::create('Name','Email','Comment')
        )
            ->addExtraClass('form-style');

        foreach($form->Fields() as $field) {
            $field->addExtraClass('form-control')
                ->setAttribute('placeholder', $field->getName().'*');
        }

        return $form;
    }*/

    public function handleComment($data, $form)
    {
        //Save into the session
        $session = $this->getRequest()->getSession();
        $session->set("FormData.{$form->getName()}.data", $data);

        $existing = $this->Comments()->filter([
            'Comment' => $data['Comment']
        ]);

        if($existing->exists() && strlen($data['Comment']) > 20) {
            $form->sessionMessage('That comment already exists! Spammer!','bad');

            return $this->redirectBack();
        }

        $comment = ArticleComment::create();
       /* $comment->Name = $data['Name'];
        $comment->Email = $data['Email'];
        $comment->Comment = $data['Comment'];*/
        $comment->ArticlePageID = $this->ID; //Lesson 11: Notice that we still have to manually assign the ArticlePageID field, as that is not present in the form data. We could have passed it via a hidden input, which would eliminate that line of code
        $form->saveInto($comment); // Replaces the above 3 assignment
        $comment->write();

        //Clear session
        $session->clear("FormData.{$form->getName()}.data");
        $form->sessionMessage('Thanks for your comment!','good');

        return $this->redirectBack();
    }

}