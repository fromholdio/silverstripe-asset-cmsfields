<?php

namespace Fromholdio\AssetCMSFields;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\Forms\TextField;
use SilverStripe\Forms\Tip;
use SilverStripe\Forms\TippableFieldInterface;
use SilverStripe\ORM\DataExtension;

class ImageFormFactoryExtension extends DataExtension
{
    public function updateFormFields(FieldList $fields, $controller, $formName, $context)
    {
        $image = isset($context['Record']) ? $context['Record'] : null;
        if ($image && in_array($image->appCategory(), ['image','vector']))
        {
            $altTextField = $fields->dataFieldByName('AltText');
            if ($altTextField)
            {
                $fields->removeByName('AltText');
                $altTextField = TextField::create('AltText', 'Alternative text (alt)');
                $altTextDescription = _t(
                    'SilverStripe\\AssetAdmin\\Controller\\AssetAdmin.AltTextTip',
                    'Description for visitors who are unable to view the image (using screenreaders or ' .
                    'image blockers). Recommended for images which provide unique context to the content.'
                );
                if ($altTextField instanceof TippableFieldInterface) {
                    $altTextField->setTip(new Tip($altTextDescription));
                } else {
                    $altTextField->setDescription($altTextDescription);
                }
                if ($this->getOwner()->hasMethod('updateFluentCMSField')) {
                    $this->getOwner()->updateFluentCMSField($altTextField);
                }

                $titleField = $fields->fieldByName('Editor.Details.Title');
                if ($titleField) {
                    if ($titleField->isReadonly()) {
                        $altTextField = $altTextField->performReadonlyTransformation();
                    }
                    $fields->insertAfter('Title', $altTextField);
                }
            }

            /** @var TabSet $rootTabSet */
            $rootTabSet = $fields->fieldByName('Editor');
            if ($rootTabSet)
            {
                $tab = Tab::create('FocusPointTab', 'Focus');
                $fpField = $fields->fieldByName('Editor.Details.FocusPoint');
                if ($fpField) {
                    $fields->removeByName('FocusPoint');
                    $tab->push($fpField);
                    $rootTabSet->insertAfter('Details', $tab);
                }
            }
        }
    }
}
