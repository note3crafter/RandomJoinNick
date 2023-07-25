<?php

namespace TheNote\RandomNick;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerLoginEvent;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener {
    /** @var array */
    private $nicknames = [];

    public function onEnable(): void {
        $this->saveResource("config.yml");
        $this->loadNicknamesFromConfig();
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }

    private function loadNicknamesFromConfig(): void {
        $config = new Config($this->getDataFolder() . "config.yml", Config::YAML);
        $this->nicknames = $config->get("nicknames", []);
    }

    public function onLogin(PlayerLoginEvent $event): void {
        $player = $event->getPlayer();
        if($player->hasPermission("randomjoin.nick")) {
            $this->setRandomNickname($player);
        }
    }
    private function setRandomNickname(Player $player): void
    {
        if (empty($this->nicknames)) {
            $player->getName();
            return;
        }

        $randomNickname = $this->nicknames[array_rand($this->nicknames)];
        $player->setDisplayName($randomNickname);
        $player->setNameTag($randomNickname);
    }
}
