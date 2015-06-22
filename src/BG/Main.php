<?php

namespace 1v1System;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\event\block\SignChangeEvent;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\math\Vector3;
use pocketmine\scheduler\PluginTask;

class Main extends PluginBase implements Listener{

    /** @var Config sign */
    public $sign;
    
  public function onEnable(){
        @mkdir($this->getDataFolder());
        $this->configFile = (new Config($this->getDataFolder()."countdown.yml", Config::YAML, array(
            "countdown" => array(
                "Starting In §45§2...",
                "Starting In §44§2...",
                "Starting In §43§2...",
                "Starting In §42§2...",
                "Starting In §41§2...",
                "GO!!!!"
            ),
            "Countdown-Color" => "§2"
        )))->getAll();

    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->saveDefaultConfig();
    $config = $this->getConfig();
    $nopermmsg = $config->get("No-Permission-Message");
    $killermsg = $config->get("Killer-Win-Message");
    $playermsg = $config->get("Player-Death-Message");
    $cornermsg = $config->get("Get-In-Corner-Message");
    $tpmsg = $config->get("Telporting-Message");
    $color = $config->get("Teleporting-Message-Color");
    $color2 = $this->confiFile->get("Countdown-Color");
    $limitmsg = $config->get("World-Full-Message");
    $createmsg = $config->get("Sign-Create-Message");
    $destroymsg = $config->get("Sign-Destroy-Message");
    $world = strtolower(trim($event->getLine(1)));
    $p = count($this->getServer()->getLevelByName($world)->getPlayers());
    $this->getLogger()->info(TextFormat::BLUE."[" . TextFormat::LIGHT_PURPLE . "1v1" . TextFormat::BLUE"]" . TextFormat::GREEN . " I've been enabled! Created By: " . TextFormat::RED . "ItzBulkDev" . TextFormat::GREEN . "and " . TextFormat::RED . "FluffyBearYT");
  }
  public function onDisable(){
    $this->saveDefaultConfig();
    $this->getLogger()->info(TextFormat::BLUE."[" . TextFormat::LIGHT_PURPLE . "1v1" . TextFormat::BLUE"]" . TextFormat::RED . " I've been disabled! Created By: " . TextFormat::GREEN . "ItzBulkDev" . TextFormat::RED . "and " . TextFormat::GREEN . "FluffyBearYT");
  }
    public function onSignChange(SignChangeEvent $event){
        $player = $event->getPlayer();
        if(strtolower(trim($event->getLine(0))) == "[1v1]" || strtolower(trim($event->getLine(0))) == "1v1"){
            if($player->hasPermission("1v1")){
                //Detects if its a 1v1 sign, changes lines
                $event->setLine(0,TextFormat::GREEN."[1v1]");
                $event->setLine(1,TextFormat::YELLOW."WORLD: [$world]");
                $event->setLine(2,TextFormat::BLUE."PLAYERS: ".TextFormat::GREEN.$p"/".TextFormat::RED."2");
                $event->setLine(3,TextFormat::LIGHT_PURPLE."");
                $this->sign->setNested("sign.x", $event->getBlock()->getX());
                $this->sign->setNested("sign.y", $event->getBlock()->getY());
                $this->sign->setNested("sign.z", $event->getBlock()->getZ());
                $this->sign->setNested("sign.enabled", true);
                $this->sign->setNested("sign.level", $world);
                $this->sign->save();
                $this->sign->reload();
                $player->sendMessage($createmsg);
            }else{
                $player->sendMessage($nopermmsg);
                $event->setCancelled();
            }
        }
    }
        public function onPlayerBreakBlock(BlockBreakEvent $event){
        if ($event->getBlock()->getID() == Item::SIGN || $event->getBlock()->getID() == Item::WALL_SIGN || $event->getBlock()->getID() == Item::SIGN_POST) {
            $signt = $event->getBlock();
            if (($tile = $signt->getLevel()->getTile($signt))){
                if($tile instanceof Sign) {
                    if ($event->getBlock()->getX() == $this->sign->getNested("sign.x") || $event->getBlock()->getY() == $this->sign->getNested("sign.y") || $event->getBlock()->getZ() == $this->sign->getNested("sign.z")) {
                        if($event->getPlayer()->hasPermission("1v1.break")) {
                            $this->sign->setNested("sign.x", $event->getBlock()->getX());
                            $this->sign->setNested("sign.y", $event->getBlock()->getY());
                            $this->sign->setNested("sign.z", $event->getBlock()->getZ());
                            $this->sign->setNested("sign.enabled", false);
                            $this->sign->setNested("sign.level", "world");
                            $this->sign->save();
                            $this->sign->reload();
                            $event->getPlayer()->sendMessage("$destroymsg");
                        }else{
                            $event->getPlayer()->sendMessage("$nopermmsg");
                            $event->setCancelled();
                        }
                    }
                }
            }
        }
        }
  
      public function onTouch(PlayerInteractEvent $event){
        $tile = $event->getBlock()->getX() == $this->sign->getNested("sign.x") || $event->getBlock()->getY() == $this->sign->getNested("sign.y") || $event->getBlock()->getZ() == $this->sign->getNested("sign.z");
        if($tile instanceof Sign){
            if(TextFormat::clean($tile->getText()[0], true) === "[1v1]"){
               $event->teleport($world->getSpawnLocation());
               foreach($event as $players)
                $event->getPlayer()->sendPopup($color ."" . $tpmsg . "");
                $event->getPlayer()->sendMessage($cornermsg);
                //Gives Full Health
                $event->getPlayer()->setHealth(20);
                }else{
                    if($p == "2"){
                        $time = intval("5") * 20;
                        $this->getServer()->getScheduler()->scheduleRepeatingTask(new 1v1System($this), $time);
                                $countdown = $this->plugin->configFile["countodown"];
                                $messagekey = array_rand($countdown, 1);
                                $countmsg = $countdown[$messagekey];
                        $players->getPlayer()->sendPopup($color2 ."" . $countmsg . "");
                        $event->getPlayer()->sendMessage($limitmsg);
                        $event->setCancelled();
                    }
                }
}
                
                }
                public function onDeath(PlayerDeathEvent $event){
                    $cause = $event->getEntity()->getLastDamageCause();
                        if($cause instanceof EntityDamageByEntityEvent) {
                            $player = $event->getEntity();
                            $killer = $event->getEntity()->getLastDamageCause()->getDamager();
                                if($killer instanceof Player) {
                                    $player->getPlayer()->sendPopup($playermsg);
                                    $killer->getPlayer()->sendPopup($killermsg);
                                    $killer->teleport($killer->getServer()->getDefaultLevel()->getSpawnLocation());
                    
            
                    
                
                
                
                        
            
