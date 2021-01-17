<?php

namespace AriefaL\arbankui;

use AriefaL\arbankui\forms\BankForm;
use onebone\economyapi\EconomyAPI;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\{PlayerJoinEvent, PlayerQuitEvent};
use pocketmine\utils\Config;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener {
	
	const QUEST_VERSION = "vMCN-1";
	const PREFIX = "§l§9[§eBankUI§9] §r";

	private static $instance;
	
	public function onLoad() {
		$this->Economy = $this->getServer()->getPluginManager()->getPlugin("EconomyAPI");
        if(!$this->Economy){
			$this->getLogger()->info("§c» Plugin EconomyAPI was not found!");
            $this->getLogger()->info("§c» Need plugin EconomyAPI!");
            $this->getServer()->getPluginManager()->disablePlugin($this);
        }
	}

	public function onEnable() {
		self::$instance = $this;
		
		$this->bank = new Config($this->getDataFolder() . "bank.yml", Config::YAML);
		$this->forms = new BankForm($this);
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}
	
	public static function getInstance(): Main {
        return self::$instance;
    }
	
	function getForms() : BankForm {
		return $this->forms;
	}
	
	public function onJoin(PlayerJoinEvent $event){
		$player = $event->getPlayer();
	}
	
	public function onQuit(PlayerQuitEvent $event) {
		$player = $event->getPlayer();
		$this->bank->save();
	}
	
	public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool {
		if($player instanceof Player) {
			if($command->getName() == "bankui"){
				if(!$this->bank->exists($player->getName())){
					$this->getForms()->createForm($player);
				}else{
					$this->getForms()->menuForm($player);
				}
			}
		}else{
			$player->sendMessage(Main::PREFIX . "§cRun Command In-Game!!");
		}
		return true;
	}
}
