<?php

namespace Jorgebyte\TpaSystem\util;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;

class Sound
{
    public static function addSound(Player $player, string $soundName, float $volume = 1.0, float $pitch = 1.0): void
    {
        $player->getNetworkSession()->sendDataPacket(
            self::createPacket($player->getPosition(), $soundName, $volume, $pitch)
        );
    }

    public static function addSoundBroadcast(array $players, string $soundName, float $volume = 1.0, float $pitch = 1.0): void
    {
        foreach ($players as $player) {
            if ($player instanceof Player) {
                self::addSound($player, $soundName, $volume, $pitch);
            }
        }
    }

    private static function createPacket(Vector3 $vec, string $soundName, float $volume = 1.0, float $pitch = 1.0): PlaySoundPacket
    {
        return PlaySoundPacket::create($soundName, $vec->x, $vec->y, $vec->z, $volume, $pitch);
    }
}
