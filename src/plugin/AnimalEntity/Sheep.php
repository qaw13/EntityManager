<?php

namespace plugin\AnimalEntity;

use pocketmine\entity\Colorable;
use pocketmine\item\Item;
use pocketmine\level\format\FullChunk;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\Compound;
use pocketmine\nbt\tag\String;
use pocketmine\Player;

class Sheep extends Animal implements Colorable{
    const NETWORK_ID = 13;

    public $width = 1.6;
    public $length = 0.8;
    public $height = 1.12;

    public function initEntity(){
        parent::initEntity();
        $this->setMaxHealth(8);
        $this->namedtag->id = new String("id", "Sheep");
        $this->lastTick = microtime(true);
        $this->created = true;
    }

    public function getTarget(){
        $target = null;
        $nearDistance = PHP_INT_MAX;
        foreach($this->hasSpawned as $p){
            $slot = $p->getInventory()->getItemInHand();
            if(($distance = $this->distanceSquared($p)) <= 36 and $p->spawned and $p->dead == false and !$p->closed){
                if($distance < $nearDistance && $slot->getId() == Item::SEEDS){
                    $target = $p;
                    $nearDistance = $distance;
                    continue;
                }
            }
        }
        if($target instanceof Player){
            return $target;
        }elseif($this->moveTime >= mt_rand(400, 800) or ($target === null and !$this->target instanceof Vector3)){
            $this->moveTime = 0;
            return $this->target = new Vector3($this->x + mt_rand(-100, 100), $this->y, $this->z + mt_rand(-100,100));
        }
        return $this->target;
    }

    public function getName(){
        return "양";
    }

}