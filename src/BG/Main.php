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
				$p->sendtip("starts in ".$this->lastTime);	
				break;
			case 10:
$p->sendTip("starts in ".$this->lastTime);
			Break;
				
				
			
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
				$p->sendMessage("The 1v1 Has Begun!");
			
					 
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
				$p->sendMessage($this->lastTime." left");
				break;
			case 10:
				$p->sendMessage("10 seconds left");
				break;
			case 30:
			$p->sendMessage("30 seconds left");
				 
				break;
				case 60:
				 	$p->sendMessage("60 seconds lefts");	
				 				 
				 				break;
			case 0: 
		$p->sendMessage("match has ended");

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

   public function setGame(PlayerInteractEvent $event){
		$player=$event->getPlayer();
		$username=$player->getName();
		$block=$event->getBlock();
		$levelname=$player->getLevel()->getFolderName();
		if(isset($this->SetStatus[$username]))
		{
			switch ($this->SetStatus[$username])
			{
			case 0:
				if($event->getBlock()->getID() != 63 && $event->getBlock()->getID() != 68) 
				{
					$player->sendMessage(TextFormat::GREEN."please choose a sign to click on");
					return;
				}
				$this->sign=array(
					"x" =>$block->getX(),
					"y" =>$block->getY(),
					"z" =>$block->getZ(),
					"level" =>$levelname);
				$this->config->set("sign",$this->sign);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."SIGN for condition has been created");
				$player->sendMessage(TextFormat::GREEN." please click on the 1st spawnpoint");
				$this->signlevel=$this->getServer()->getLevelByName($this->config->get("sign")["level"]);
				$this->sign=new Vector3($this->sign["x"],$this->sign["y"],$this->sign["z"]);
				$this->changeStatusSign();
				break;
			case 1:
				$this->pos1=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->config->set("pos1",$this->pos1);
				$this->config->save();
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN."  Spawnpoint 1 created");
				$player->sendMessage(TextFormat::GREEN." Please click on the 2nd spawnpoint");
				$this->pos1=new Vector3($this->pos1["x"]+0.5,$this->pos1["y"],$this->pos1["z"]+0.5);
				break;
			case 2:
				 $this->pos2=array(
					"x" =>$block->x,
					"y" =>$block->y,
					"z" =>$block->z,
					"level" =>$levelname);
				$this->pos2=new Vector3($this->pos2["x"]+0.5,$this->pos2["y"],$this->pos2["z"]+0.5);
				$this->SetStatus[$username]++;
				$player->sendMessage(TextFormat::GREEN." spawnpoint 2 created");
				$this->config->set("pos2",$this->pos2);
				$this->config->save();
				$player->sendMessage(TextFormat::GREEN."All settings completed, you can start a game now.");
				$this->level=$this->getServer()->getLevelByName($this->config->get("pos1")["level"]);	
				break;

			}
		}
		else
		{
			$sign=$event->getPlayer()->getLevel()->getTile($event->getBlock());
			/* if($this->PlayerIsInGame($event->getPlayer()->getName()){
				 $event->setCancelled(true);
				 }else{*/
			if(isset($this->pos2) && $this->pos2!=array() && $sign instanceof Sign && $sign->getX()==$this->sign->x && $sign->getY()==$this->sign->y && $sign->getZ()==$this->sign->z && $event->getPlayer()->getLevel()->getFolderName()==$this->config->get("sign")["level"])
			{
			 
			
				if(!$this->config->exists("pos2"))
				{
				 
					$event->getPlayer()->sendMessage("You can not join the game for the game hasn't been set yet");
					return;
				}
				if(!$event->getPlayer()->hasPermission("1v1.join.game"))
				{
					$event->getPlayer()->sendMessage("§l§cYou don't have permission to join this game");
					return;
				}
				$player = $event->getPlayer();
				 		if(isset($this->players[$player->getName()]))
		{	

		return;
		}
				if($this->gameStatus==0 || $this->gameStatus==1)
				{
					if(!isset($this->players[$event->getPlayer()->getName()]))
					{
						if(count($this->players)===2)
						{
						
							return;
						}  

						$this->players[$event->getPlayer()->getName()]=array("id"=>$event->getPlayer()->getName());
						
						if($this->gameStatus==0 && count($this->players)===2)
						{
						 
							$this->gameStatus=1;
							$this->lastTime=$this->waitTime;
						}
						if(count($this->players)===2 && $this->gameStatus==1 && $this->lastTime>5)
						{
						// message here
							$this->lastTime=10;
						}
						$this->changeStatusSign();
					}
					else
					{
		if($this->PlayerIsInGame($event->getPlayer()->getName())){
		$event->setCancelled(true);
		//message here
					}
					}
				}
				else
				{
				//message here
				}
			}
		//}

	}
	} 
}
