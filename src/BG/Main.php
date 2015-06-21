<?php

namespace 1v1System;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener{

  public function onEnable(){
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveDefaultConfig();
    $config = $this->getConfig();
    $this->getLogger()->info(TextFormat::BLUE."[" . TextFormat::LIGHT_PURPLE . "1v1" . TextFormat::BLUE"]" . TextFormat::GREEN . " I've been enabled! Created By: " . TextFormat::RED . "ItzBulkDev" . TextFormat::GREEN . "and " . TextFormat::RED . "FluffyBearYT");
  }
  public function onDisable(){
    $this->saveDefaultConfig();
    $this->getLogger()->info(TextFormat::BLUE."[" . TextFormat::LIGHT_PURPLE . "1v1" . TextFormat::BLUE"]" . TextFormat::RED . " I've been disabled! Created By: " . TextFormat::GREEN . "ItzBulkDev" . TextFormat::RED . "and " . TextFormat::GREEN . "FluffyBearYT");
  }
      public function onSignTap(PlayerInteractEvent $event){
        $tile = $event->getBlock()->getLevel()->getTile(new Vector3($event->getBlock()->getFloorX(), $event->getBlock()->getFloorY(), $event->getBlock()->getFloorZ()));
        if($tile instanceof Sign){
            // Free sign
            if(TextFormat::clean($tile->getText()[0], true) === "[1v1]"){
                $event->setCancelled(true);
                if(!$event->getPlayer()->hasPermission("1v1.sign")){
                    $event->getPlayer()->sendMessage(TextFormat::RED . "You don't have permissions to use this sign");
                      }elseif{
                        
            
