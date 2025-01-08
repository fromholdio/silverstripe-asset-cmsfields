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
            if ($altTextField && $altTextField instanceof TippableFieldInterface)
            {
                $altTextField->getTip()->setImportanceLevel(Tip::IMPORTANCE_LEVELS['NORMAL']);
            }

            /** @var TabSet $rootTabSet */
            $rootTabSet = $fields->fieldByName('Editor');
            if ($rootTabSet)
            {
                $tab = Tab::create('FocusPointTab', 'Focus');
                $fpField = $fields->fieldByName('Editor.Details.FocusPoint');
                if ($fpField)
                {
                    $fields->removeByName('FocusPoint');
                    $tab->push($fpField);
                    $rootTabSet->insertAfter('Details', $tab);
                }
            }
        }
    }
}
