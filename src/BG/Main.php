<?php

namespace OnevOne;

use pocketmine\Server;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\math\Vector3;
use pocketmine\tile\Sign;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\scheduler\CallbackTask;

class Main extends PluginBase implements Listener{
	public players = array();
   public function onEnable(){
      $this->getServer()->getLogger()->info("[1v1]Enabled!");
      $this->level=$this->getServer()->getLevelByName($this->config->get("pos1")["level"]);
      	   $this->signlevel=$this->getServer()->getLevelByName($this->config->get("sign")["level"]);
			
		if($this->config->exists("pos2"))
		{
			$this->sign=new Vector3($this->sign["x"],$this->sign["y"],$this->sign["z"]);
			$this->pos1=new Vector3($this->pos1["x"]+0.5,$this->pos1["y"],$this->pos1["z"]+0.5);
			$this->pos2=new Vector3($this->pos2["x"]+0.5,$this->pos2["y"],$this->pos2["z"]+0.5);
			$this->sign=$this->config->get("sign");
			$this->pos1=$this->config->get("pos1");
			$this->pos2=$this->config->get("pos2");
		}
		$this->gameTime=(int)$this->config->get("gameTime");
		$this->waitTime=(int)$this->config->get("waitTime");
		$this->gameStatus=0;
		$this->lastTime=0;
		$this->SetStatus=array();
      
   }

	public function match(){
		if(!isset($this->pos2) || $this->pos2===array())
		{
			return;
		}
		if(!$this->signlevel instanceof Level)
		{
			$this->signlevel=$this->getServer()->getLevelByName($this->config->get("sign")["level"]);
			if(!$this->signlevel instanceof Level)
			{
				return;
			}
		}
		$this->changeStatusSign();
		if($this->gameStatus===0)
		{
	
		}
		if($this->gameStatus===1)
		{
			$this->lastTime--;
			$i=0;
			foreach($this->players as $key=>$val)
			{
					$p=$this->getServer()->getPlayer($val["id"]);
					 				
			 				 if(isset($this->players[$p->getName()])){
			 				 
			switch($this->lastTime)
			{
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 10:

			case 30:
			
				break;
			case 60:
			
				break;
			case 90:
			

				break;
			case 120:
		

				break;
			case 150:
				

				break;
			case 0:
				
				
			
			$this->gameStatus=2;
			
				break;
		}
		 
$this->changeStatusSign();		 
		} 
		 
		}
		}
		if($this->gameStatus===2)
		{
			$this->lastTime--;
			if($this->lastTime<=0)
			{
			$i=0;
			 			foreach($this->players as $key=>$val)
				{
				$i++;
					$p=$this->getServer()->getPlayer($val["id"]);
				eval("\$p->teleport(\$this->pos".$i.");");
			
			
					 
			}
		}
		 				$this->gameStatus=3;
		 				$this->lastTime=$this->gameTime;
		}
		
		 		if($this->gameStatus===3)
		 		{
		 		 				foreach($this->players as $key=>$val)
				{
					$p=$this->getServer()->getPlayer($val["id"]);
			
 	 if(isset($this->players[$p->getName()])){
			$this->lastTime--;
			switch($this->lastTime)
			{
			case 1:
			case 2:
			case 3:
			case 4:
			case 5:
			case 10:
			case 30:
			
				 
				break;
				case 60:
				 			
				 				 
				 				break;
			case 0: 
		

				foreach($this->players as $pl)
				{
					$p=$this->getServer()->getPlayer($pl["id"]);
					$p->setLevel($this->signlevel);
					$p->teleport($this->signlevel->getSpawnLocation());
					$p->getInventory()->clearAll();
					$p->setHealth(20);
					$p->setGameMode(0);
					$p->removeAllEffects();
					$this->removePlayer($p);
			}

				$this->players=array();
				$this->gameStatus=0;
				$this->lastTime=0;
				$this->ClearAllInv();
				break;
				}
				$this->changeStatusSign();
				}
				}
				}
}

   
