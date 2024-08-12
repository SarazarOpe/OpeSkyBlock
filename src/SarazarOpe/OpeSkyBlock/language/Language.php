<?php

namespace SarazarOpe\OpeSkyBlock\language;

use SarazarOpe\OpeSkyBlock\OpeSkyBlock;
use pocketmine\utils\Config;

class Language {

  private static $language;

  public static function init() {
    self::$language = OpeSkyBlock::getInstance()->getDataManager()->get('language-file', "english");
  }

  public static function getLanguage() {
    return new Config(OpeSkyBlock::getInstance()->getDataFolder() . '/language/' . self::$language . '.yml', Config::YAML);
  }
}
