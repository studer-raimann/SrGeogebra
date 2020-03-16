<?php

namespace srag\Plugins\SrGeogebra\Config;

use srag\Plugins\SrGeogebra\Forms\BaseAdvancedGeogebraFormGUI;
use srag\Plugins\SrGeogebra\Utils\SrGeogebraTrait;
use ilSrGeogebraPlugin;
use srag\ActiveRecordConfig\SrGeogebra\Config\AbstractFactory;
use srag\ActiveRecordConfig\SrGeogebra\Config\AbstractRepository;
use srag\ActiveRecordConfig\SrGeogebra\Config\Config;

/**
 * Class Repository
 *
 * Generated by SrPluginGenerator v1.3.4
 *
 * @package srag\Plugins\SrGeogebra\Config
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
final class Repository extends AbstractRepository
{

    use SrGeogebraTrait;
    const PLUGIN_CLASS_NAME = ilSrGeogebraPlugin::class;
    /**
     * @var self
     */
    protected static $instance = null;


    /**
     * @return self
     */
    public static function getInstance() : self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    /**
     * Repository constructor
     */
    protected function __construct()
    {
        parent::__construct();
    }


    /**
     * @inheritDoc
     *
     * @return Factory
     */
    public function factory() : AbstractFactory
    {
        return Factory::getInstance();
    }


    /**
     * @inheritDoc
     */
    protected function getTableName() : string
    {
        return ilSrGeogebraPlugin::PLUGIN_ID . "_config";
    }


    /**
     * @inheritDoc
     */
    public function getFields() : array
    {
        return [
            ConfigAdvancedGeogebraFormGUI::KEY_DEFAULT_WIDTH => [Config::TYPE_INTEGER, 800],
            ConfigAdvancedGeogebraFormGUI::KEY_DEFAULT_HEIGHT => [Config::TYPE_INTEGER, 600],
            ConfigAdvancedGeogebraFormGUI::KEY_DEFAULT_DRAG_ZOOM => [Config::TYPE_BOOLEAN, true],
            ConfigAdvancedGeogebraFormGUI::KEY_DEFAULT_RESET => [Config::TYPE_BOOLEAN, false],
            ConfigAdvancedGeogebraFormGUI::KEY_DEFAULT_ALIGNMENT => [Config::TYPE_STRING, "left"],
            BaseAdvancedGeogebraFormGUI::KEY_APP_NAME => [Config::TYPE_STRING, "classic"],
            BaseAdvancedGeogebraFormGUI::KEY_BORDER_COLOR => [Config::TYPE_STRING, "ffffff"],
            BaseAdvancedGeogebraFormGUI::KEY_ENABLE_RIGHT => [Config::TYPE_BOOLEAN, true],
            BaseAdvancedGeogebraFormGUI::KEY_ENABLE_LABEL_DRAGS => [Config::TYPE_BOOLEAN, true],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_ZOOM => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_ERROR_DIALOGS => [Config::TYPE_BOOLEAN, true],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_MENU_BAR => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_TOOL_BAR => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_TOOL_BAR_HELP => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_ALGEBRA_INPUT => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_LANGUAGE => [Config::TYPE_STRING, "en"],
            BaseAdvancedGeogebraFormGUI::KEY_ALLOW_STYLE_BAR => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_USE_BROWSER_FOR_JS => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_LOGGING => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_CAPTURING_THRESHOLD => [Config::TYPE_DOUBLE, 3],
            BaseAdvancedGeogebraFormGUI::KEY_ENABLE_3D => [Config::TYPE_BOOLEAN, true],
            BaseAdvancedGeogebraFormGUI::KEY_ENABLE_CAS => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_ALGEBRA_INPUT_POSITION => Config::TYPE_STRING,
            BaseAdvancedGeogebraFormGUI::KEY_PREVENT_FOCUS => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_AUTO_HEIGHT => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_ALLOW_UPSCALE => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_PLAY_BUTTON => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SCALE => [Config::TYPE_DOUBLE, 1],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_ANIMATION_BUTTON => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_FULLSCREEN_BUTTON => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_SUGGESTION_BUTTONS => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_SHOW_START_TOOLTIP => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_ROUNDING => Config::TYPE_STRING,
            BaseAdvancedGeogebraFormGUI::KEY_BUTTON_SHADOWS => [Config::TYPE_BOOLEAN, false],
            BaseAdvancedGeogebraFormGUI::KEY_BUTTON_ROUNDING => [Config::TYPE_DOUBLE, 0.2]
        ];
    }
}
