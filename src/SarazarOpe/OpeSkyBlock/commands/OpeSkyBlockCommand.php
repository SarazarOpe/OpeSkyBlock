<?php

namespace SarazarOpe\OpeSkyBlock\commands;

use SarazarOpe\OpeSkyBlock\OpeSkyBlock;
use SarazarOpe\OpeSkyBlock\form\FormManager;
use pocketmine\plugin\PluginOwned;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class OpeSkyBlockCommand extends Command implements PluginOwned {

  public function __construct() {
    parent::__construct("skyblock", "SkyBlock menu", null, ["sb", "island", "is"]);
    $this->setPermission("osb.command");
  }

  public function execute(CommandSender $sender, string $label, array $args) {
    $form = new FormManager();
    if ($sender instanceof Player) $form->startForm($sender);
  }

  public function getOwningPlugin(): OpeSkyBlock {
    return OpeSkyBlock::getInstance();
  }
}
