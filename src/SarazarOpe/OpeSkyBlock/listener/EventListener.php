<?php

namespace SarazarOpe\OpeSkyBlock\listener;

use SarazarOpe\OpeSkyBlock\OpeSkyBlock;
use pocketmine\event\Listener;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityItemPickupEvent;
use pocketmine\event\entity\EntityTrampleFarmlandEvent;
use pocketmine\player\Player;

class EventListener implements Listener {

    public function onDamage(EntityDamageByEntityEvent $event) {
        $player = $event->getDamager();
        $entity = $event->getEntity();
        $skyblock = OpeSkyBlock::getInstance()->getSkyBlock();
        $worldName = $player->getWorld()->getFolderName();
        if ($player instanceof Player && $entity instanceof Player) {
            if ($skyblock->haveIsland($worldName)) {
                if ($skyblock->allowPvP($worldName) || $player->hasPermission("osb.bybass")) {
                    return true;
                }
                $event->cancel();
            }
        }
    }

    public function onTrample(EntityTrampleFarmlandEvent $event) {
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        $name = strtolower($player->getName());
        $skyblock = OpeSkyBlock::getInstance()->getSkyBlock();
        $worldName = $player->getWorld()->getFolderName();
        if ($skyblock->haveIsland($worldName)) {
            if ($worldName == $name || $skyblock->isFriends($worldName, $name) || $player->hasPermission("osb.bybass")) {
                return true;
            }
            $event->cancel();
        }
    }

    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $name = strtolower($player->getName());
        $skyblock = OpeSkyBlock::getInstance()->getSkyBlock();
        $worldName = $player->getWorld()->getFolderName();
        if ($skyblock->haveIsland($worldName)) {
            if ($worldName == $name || $skyblock->isFriends($worldName, $name) || $player->hasPermission("osb.bybass")) {
                return true;
            }
            $event->cancel();
        }
    }

    public function onBreak(BlockBreakEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $name = strtolower($player->getName());
        $skyblock = OpeSkyBlock::getInstance()->getSkyBlock();
        $worldName = $player->getWorld()->getFolderName();
        $block = $event->getBlock();
        $blocks = $skyblock->getBlocks();
        $blockName = str_replace(' ', '_', strtolower($block->getName()));
        if ($skyblock->haveIsland($worldName)) {
            if ($worldName == $name || $skyblock->isFriends($worldName, $name) || $player->hasPermission("osb.bybass")) {
                if (array_key_exists($blockName, $blocks)) {
                    $skyblock->takePoints($worldName, $blocks[$blockName]);
                }
                return true;
            } else {
                $event->cancel();
                return true;
            }
        }
    }

    public function onPlace(BlockPlaceEvent $event) {
        if ($event->isCancelled()) return;
        $player = $event->getPlayer();
        $worldName = $player->getWorld()->getFolderName();
        $skyblock = OpeSkyBlock::getInstance()->getSkyBlock();
        $blocks = $skyblock->getBlocks();
        $blockTransaction = $event->getTransaction()->getBlocks();
        $blockName = null;
        if ($skyblock->haveIsland($worldName)) {
            foreach ($blockTransaction as [$x, $y, $z, $block]) {
                $blockName = str_replace(' ', '_', strtolower($block->getName()));
            }
            if (array_key_exists($blockName, $blocks)) {
                $skyblock->setPoints($worldName, $blocks[$blockName]);
            }
        }
    }

    public function onPickup(EntityItemPickupEvent $event) {
        $entity = $event->getEntity();
        $skyblock = OpeSkyBlock::getInstance()->getSkyBlock();
        $worldName = $entity->getWorld()->getFolderName();
        if ($entity instanceof Player) {
            $name = strtolower($entity->getName());
            if ($skyblock->haveIsland($worldName)) {
                if ($worldName == $name || $skyblock->isFriends($worldName, $name) || $entity->hasPermission("osb.bybass")) {
                    return true;
                }
                $event->cancel();
            }
        }
    }
}
