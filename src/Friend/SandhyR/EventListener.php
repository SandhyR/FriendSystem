<?php

namespace Friend\SandhyR;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

class EventListener implements Listener{

    public function onJoin(PlayerJoinEvent $event){
        $playername = $event->getPlayer()->getName();
        if(Friend::getInstance()->getDatabase()->query("SELECT * FROM friend WHERE playername='$playername'")->fetch_row() == null){
            $array = [];
            $array = base64_encode(serialize($array));
            Friend::getInstance()->getDatabase()->query("INSERT INTO friend VALUES(null, '$playername', '$array')");
        } else {
            $manager = new FriendManager();
            $array = $manager->getArrayFriend($event->getPlayer());
            foreach ($array as $p){
                $player = Server::getInstance()->getPlayerExact($p);
                if($player->isOnline()){
                    $player->sendMessage("FRIEND > {$event->getPlayer()->getName()} Join the server");
                }
            }
        }
    }
}
