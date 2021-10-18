<?php

namespace Friend\SandhyR;

use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\Config;

class Friend extends PluginBase{

    /*
░██████╗░█████╗░███╗░░██╗██████╗░██╗░░██╗██╗░░░██╗██████╗░
██╔════╝██╔══██╗████╗░██║██╔══██╗██║░░██║╚██╗░██╔╝██╔══██╗
╚█████╗░███████║██╔██╗██║██║░░██║███████║░╚████╔╝░██████╔╝
░╚═══██╗██╔══██║██║╚████║██║░░██║██╔══██║░░╚██╔╝░░██╔══██╗
██████╔╝██║░░██║██║░╚███║██████╔╝██║░░██║░░░██║░░░██║░░██║
╚═════╝░╚═╝░░╚═╝╚═╝░░╚══╝╚═════╝░╚═╝░░╚═╝░░░╚═╝░░░╚═╝░░╚═╝*/

    public static $instance;

    private $config;

    public function onLoad()
    {
        self::$instance = $this;
    }

    public function onEnable()
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->config = new Config($this->getDataFolder() . "config.yml", Config::YAML, array());
        $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
        $this->initdb();
        $this->initcmd();
    }

    public static function getInstance(){
        return self::$instance;
    }

    public function initdb(){
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS friend (id INT PRIMARY KEY AUTO_INCREMENT, playername VARCHAR(255) NOT NULL, friends VARCHAR(255) NOT NULL);");
        $this->getDatabase()->query("CREATE TABLE IF NOT EXISTS request (id INT PRIMARY KEY AUTO_INCREMENT, player1name VARCHAR(255) NOT NULL, player2name VARCHAR(255) NOT NULL);");
    }

    public function initcmd(){
        $this->getServer()->getCommandMap()->register("Friend", new FriendCommand("friend", "Friend Command"));
    }

    public function getDatabase()
    {
        return new \mysqli($this->config->get("host"), $this->config->get("user"), $this->config->get("password"), $this->config->get("db-name"));
    }

}
