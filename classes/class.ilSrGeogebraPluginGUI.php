<?php

use ILIAS\FileUpload\DTO\ProcessingStatus;
use ILIAS\FileUpload\DTO\UploadResult;
use ILIAS\FileUpload\Location;
use srag\ActiveRecordConfig\SrGeogebra\Config\Config;
use srag\Plugins\SrGeogebra\Config\Repository;
use srag\Plugins\SrGeogebra\Forms\GeogebraFormGUI;
use srag\Plugins\SrGeogebra\Forms\SettingsAdvancedGeogebraFormGUI;
use srag\Plugins\SrGeogebra\Upload\UploadService;
use srag\Plugins\SrGeogebra\Utils\SrGeogebraTrait;
use srag\DIC\SrGeogebra\DICTrait;

/**
 * Class ilSrGeogebraPluginGUI
 *
 * Generated by SrPluginGenerator v1.3.4
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 *
 * @ilCtrl_isCalledBy ilSrGeogebraPluginGUI: ilPCPluggedGUI
 */
class ilSrGeogebraPluginGUI extends ilPageComponentPluginGUI
{

    use DICTrait;
    use SrGeogebraTrait;
    const PLUGIN_CLASS_NAME = ilSrGeogebraPlugin::class;
    const CMD_CANCEL = "cancel";
    const CMD_CREATE = "create";
    const CMD_CREATE_ADVANCED = "createAdvanced";
    const CMD_EDIT = "edit";
    const CMD_EDIT_ADVANCED = "editAdvanced";
    const CMD_INSERT = "insert";
    const CMD_UPDATE = "update";
    const CMD_UPDATE_ADVANCED_PROPERTIES = "updateAdvancedProperties";
    const SUBTAB_GENERIC_SETTINGS = "subtab_generic_settings";
    const SUBTAB_ADVANCED_SETTINGS = "subtab_advanced_settings";
    const ID_PREFIX = "geogebra_page_component_";


    /**
     * @var int
     */
    protected static $id_counter = 0;
    /**
     * @var UploadService
     */
    protected $uploader;
    protected $pl;


    /**
     * ilSrGeogebraPluginGUI constructor
     */
    public function __construct()
    {
        parent::__construct();
        $this->pl = new ilSrGeogebraPlugin();
        $this->uploader = new UploadService();
    }


    /**
     * @inheritDoc
     */
    public function executeCommand()/*:void*/
    {
        $next_class = self::dic()->ctrl()->getNextClass($this);

        switch (strtolower($next_class)) {
            default:
                $cmd = self::dic()->ctrl()->getCmd();

                switch ($cmd) {
                    case self::CMD_CANCEL:
                    case self::CMD_CREATE:
                    case self::CMD_EDIT:
                    case self::CMD_EDIT_ADVANCED:
                    case self::CMD_INSERT:
                    case self::CMD_UPDATE:
                    case self::CMD_UPDATE_ADVANCED_PROPERTIES:
                        $this->{$cmd}();
                        break;

                    default:
                        break;
                }
                break;
        }
    }


    /**
     * @param string $properties
     *
     * @return ilPropertyFormGUI
     */
    protected function getForm($properties = "") : ilPropertyFormGUI
    {
        if (empty($properties)) {
            $form = new GeogebraFormGUI($this);
        } else {
            $form = new GeogebraFormGUI($this, $properties);
        }

        return $form;
    }


    /**
     * @inheritDoc
     */
    public function insert()/*:void*/
    {
        $this->edit();
    }


    /**
     * @inheritDoc
     */
    public function create()/*:void*/
    {
        $form = $this->getForm();
        $form->setValuesByPost();

        if (!$form->checkInput()) {
            self::output()->output($form);

            return;
        }

        $file_name = $this->uploader->handleUpload($form, $_FILES["file"]["name"]);

        $properties = [
            "title" => $_POST["title"],
            "legacyFileName" => $file_name,
            "fileName"       => $file_name
        ];

        $properties = $this->mergeCustomSettings($properties);
        $properties = $this->mergeAdvancedSettings($properties);

        $this->createElement($properties);
        $this->returnToParent();
    }


    protected function mergeCustomSettings(&$properties) {
        $customSettings = Repository::CUSTOM_SETTINGS;
        $formatedCustomSettings = [];

        foreach ($customSettings as $custom_setting) {
            $key = str_replace("custom_", "", $custom_setting);
            $formatedCustomSettings[$custom_setting] = $_POST[$key];
        }

        return array_merge($properties, $formatedCustomSettings);
    }


    protected function mergeAdvancedSettings(&$properties) {
        $occurringValues = Repository::getInstance()->getFields();
        $advancedSettings = [];

        foreach ($occurringValues as $key => $occurring_value) {
            if (strpos($key, "default_") !== 0) {
                $value = Repository::getInstance()->getValue($key);
                $advancedSettings["advanced_" . $key] = $value;
            }
        }

        return array_merge($properties, $advancedSettings);
    }


    /**
     * @inheritDoc
     */
    public function edit()/*:void*/
    {
        if (!empty($this->getProperties())) {
            $this->setSubTabs(self::SUBTAB_GENERIC_SETTINGS);
        }

        $form = $this->getForm($this->getProperties());

        self::output()->output($form);
    }


    public function editAdvanced() {
        $this->setSubTabs(self::SUBTAB_ADVANCED_SETTINGS);
        $form = new SettingsAdvancedGeogebraFormGUI($this, $this->getProperties());

        self::output()->output($form);
    }


    /**
     *
     */
    public function update()/*:void*/
    {
        $properties = $this->getProperties();
        $form = $this->getForm($properties);
        $form->setValuesByPost();

        if (!$form->checkInput()) {
            self::output()->output($form);

            return;
        }

        if (!empty($_FILES["file"]["name"])) {
            $fileName = $this->uploader->handleUpload($form, $_FILES["file"]["name"]);

            $properties["legacyFileName"] = $fileName;
            $properties["fileName"] = $fileName;
        }

        $properties["title"] = $_POST["title"];
        $this->updateElement($properties);

        $this->updateCustomProperties();
        $this->returnToParent();
    }


    /**
     *
     */
    public function cancel()/*:void*/
    {
        $this->returnToParent();
    }


    protected function loadJS()
    {
        self::dic()->ui()->mainTemplate()->addJavaScript($this->pl->getDirectory() . '/js/deployggb.js');
        self::dic()->ui()->mainTemplate()->addJavaScript($this->pl->getDirectory() . '/js/ggb_create.js');
    }


    protected function loadCSS(){
        self::dic()->ui()->mainTemplate()->addCss($this->pl->getDirectory() . '/css/geogebra_sheet.css');
    }


    protected function setSubTabs($active) {
        self::dic()->tabs()->addSubTab(
            self::SUBTAB_GENERIC_SETTINGS,
            $this->pl->txt(self::SUBTAB_GENERIC_SETTINGS),
            self::dic()->ctrl()->getLinkTarget($this, self::CMD_EDIT)
        );
        self::dic()->tabs()->addSubTab(
            self::SUBTAB_ADVANCED_SETTINGS,
            $this->pl->txt(self::SUBTAB_ADVANCED_SETTINGS),
            self::dic()->ctrl()->getLinkTarget($this, self::CMD_EDIT_ADVANCED)
        );
        self::dic()->tabs()->setSubTabActive($active);
    }


    protected function updateCustomProperties() {
        $existing_properties = $this->getProperties();
        $all_custom_properties = Repository::CUSTOM_SETTINGS;

        foreach ($existing_properties as $key => $existing_property) {
            if (strpos($key, "custom_") === 0) {
                unset($all_custom_properties[$key]);
                $postKey = str_replace("custom_", "", $key);
                $existing_properties[$key] = $_POST[$postKey];
            }
        }

        // Add remaining, newly added properties
        foreach ($all_custom_properties as $key) {
            if (strpos($key, "custom_") === 0) {
                $postKey = str_replace("custom_", "", $key);
                $existing_properties[$key] = $_POST[$postKey];
            }
        }

        $this->updateElement($existing_properties);
    }


    protected function updateAdvancedProperties() {
        $existing_properties = $this->getProperties();

        foreach ($existing_properties as $key => $existing_property) {
            if (strpos($key, "advanced_") === 0) {
                $postKey = str_replace("advanced_", "", $key);
                $existing_properties[$key] = $_POST[$postKey];
            }
        }

        $this->updateElement($existing_properties);
        $this->editAdvanced();
    }


    protected function convertValueByType($type, $value) {
        if ($type === Config::TYPE_INTEGER) {
            return intval($value);
        } else if ($type === Config::TYPE_DOUBLE) {
            return doubleval($value);
        } else if ($type === Config::TYPE_BOOLEAN) {
            return boolval($value);
        }

        return $value;
    }


    protected function fetchCustomFieldTypes($field_name) {
        switch ($field_name) {
            case "width":
            case "height":
                return Config::TYPE_INTEGER;
                break;
            case "enableShiftDragZoom":
            case "showResetIcon":
                return Config::TYPE_BOOLEAN;
                break;
        }

        return Config::TYPE_STRING;
    }


    protected function convertPropertyValueTypes(&$properties) {
        foreach ($properties as $key => $property) {
            if (strpos($key, "custom_") === 0) {
                $postKey = str_replace("custom_", "", $key);
                $field_type = $this->fetchCustomFieldTypes($postKey);
                $properties[$key] = $this->convertValueByType($field_type, $property);
            }

            if (strpos($key, "advanced_") === 0) {
                $postKey = str_replace("advanced_", "", $key);
                $field_type = Repository::getInstance()->getFields()[$postKey][0];
                $properties[$key] = $this->convertValueByType($field_type, $property);
            }
        }
    }


    /**
     * @inheritDoc
     */
    public function getElementHTML(/*string*/ $a_mode, array $a_properties, /*string*/ $plugin_version) : string
    {
        // Workaround fix learning module override global template
        self::dic()->dic()->offsetUnset("tpl");
        self::dic()->dic()->offsetSet("tpl", $GLOBALS["tpl"]);

        self::$id_counter += 1;
        $id = self::ID_PREFIX . self::$id_counter;
        $plugin_dir = $this->pl->getDirectory();
        $file_name = ILIAS_WEB_DIR . '/' . CLIENT_ID . '/' . UploadService::DATA_FOLDER . '/' . $a_properties["fileName"];

        $raw_alignment = $a_properties["custom_alignment"];
        $alignment = is_null($raw_alignment) || empty($raw_alignment) ? GeogebraFormGUI::DEFAULT_ALIGNMENT : $raw_alignment;

        $this->loadJS();
        $this->loadCSS();

        $tpl = $template = self::plugin()->template("tpl.geogebra.html");
        $tpl->setVariable("ID", $id);
        $tpl->setVariable("ALIGNMENT", $alignment);

        // $a_properties value types need to be converted here as values only get saved as strings
        $this->convertPropertyValueTypes($a_properties);

        self::dic()->ui()->mainTemplate()->addOnLoadCode('GeogebraPageComponent.create("' . $id . '", "' . $plugin_dir . '", "' . $file_name . '", ' . json_encode($a_properties). ');');

        return $tpl->get();
    }
}