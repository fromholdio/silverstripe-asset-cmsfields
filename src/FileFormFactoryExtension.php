<?php

namespace Fromholdio\AssetCMSFields;

use SilverStripe\Forms\FieldList;
use SilverStripe\Forms\Tab;
use SilverStripe\Forms\TabSet;
use SilverStripe\ORM\DataExtension;

class FileFormFactoryExtension extends DataExtension
{
    public function updateFormFields(FieldList $fields, $controller, $formName, $context)
    {
        $file = isset($context['Record']) ? $context['Record'] : null;
        if ($file)
        {
            /** @var TabSet $rootTabSet */
            $rootTabSet = $fields->fieldByName('Editor');
            if ($rootTabSet)
            {
                $tab = Tab::create('SettingsTab', 'Settings');
                $nameField = $fields->fieldByName('Editor.Details.Name');
                if ($nameField) {
                    $fields->removeByName('Name');
                    $tab->push($nameField);
                }
                $createdField = $fields->fieldByName('Editor.Details.Created');
                if ($createdField) {
                    $fields->removeByName('Created');
                    $tab->push($createdField);
                }
                $editedField = $fields->fieldByName('Editor.Details.LastEdited');
                if ($editedField) {
                    $fields->removeByName('LastEdited');
                    $tab->push($editedField);
                }
                $rootTabSet->insertAfter('Permissions', $tab);

                $titleField = $fields->fieldByName('Editor.Details.Title');
                if ($titleField && $this->getOwner()->hasMethod('updateFluentCMSField')) {
                    $this->getOwner()->updateFluentCMSField($titleField);
                }
            }
        }
    }
}
