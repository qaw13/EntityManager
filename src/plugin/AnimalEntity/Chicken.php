<?php

namespace plugin\AnimalEntity;

use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\String;
use pocketmine\Player;

class Chicken extends Animal{
    const NETWORK_ID = 10;

    public $width = 0.4;
    public $height = 0.75;

    public function getName(){
        return "닭";
    }

    public function initEntity(){
        parent::initEntity();
        $this->setMaxHealth(4);
        $this->namedtag->id = new String("id", "Chicken");
        $this->lastTick = microtime(true);
        $this->created = true;
    }

    public function getTarget(){
        if(!$this->isMovement()) return new Vector3();
        if($this->stayTime > 0){
            if($this->stayVec === null or mt_rand(1, 40) <= 4) $this->stayVec = $this->add(mt_rand(-10, 10), 0, mt_rand(-10, 10));
            return $this->stayVec;
        }
        $target = null;
        $nearDistance = PHP_INT_MAX;
        foreach($this->hasSpawned as $p){
            $slot = $p->getInventory()->getItemInHand();
            if(($distance = $this->distanceSquared($p)) <= 36 and $p->spawned and $p->dead == false and !$p->closed){
                if($distance < $nearDistance && $slot->getID() == Item::SEEDS){
                    $target = $p;
                    $nearDistance = $distance;
                    continue;
                }
            }
        }
        if($target === null && $this->stayTime <= 0 && mt_rand(1, 420) === 1){
            $this->stayTime = mt_rand(82, 400);
            return $this->stayVec = $this->add(mt_rand(-10, 10), 0, mt_rand(-10, 10));
        }
        if($target instanceof Player){
            return $target;
        }elseif($this->moveTime >= mt_rand(400, 800) or ($target === null and !$this->target instanceof Vector3)){
            $this->moveTime = 0;
            $this->target = new Vector3($this->x + mt_rand(-100, 100), $this->y, $this->z + mt_rand(-100,100));
        }
        return $this->target;
    }
}