<?php

namespace Jorgebyte\TpaSystem\manager;

use Jorgebyte\TpaSystem\Main;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;

class TpaManager
{
    /** @var array */
    private $tpaRequests = [];

    /** @var int */
    private $expirationTime;

    public function __construct(private readonly Main $plugin)
    {
        $this->expirationTime = $plugin->getConfig()->get("tpa_expiration_time");
    }

    public function sendTpaRequest(Player $from, Player $to): void
    {
        $this->tpaRequests[$to->getName()] = [
            "from" => $from->getName(),
            "time" => time()
        ];
        ;

        $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($to): void {
            $this->checkExpiredTpaRequest($to);
        }), 20 * $this->expirationTime);
    }

    public function acceptTpaRequest(Player $receiver): bool
    {
        $name = $receiver->getName();
        if ($this->hasPendingTpaRequest($receiver)) {
            $request = $this->tpaRequests[$name];
            $senderName = $request["from"];
            $sender = $receiver->getServer()->getPlayerExact($senderName);

            if ($sender instanceof Player) {
                $sender->teleport($receiver->getPosition());
                unset($this->tpaRequests[$name]);
                return true;
            }
        }
        return false;
    }

    public function denyTpaRequest(Player $receiver): bool
    {
        $name = $receiver->getName();
        if ($this->hasPendingTpaRequest($receiver)) {
            unset($this->tpaRequests[$name]);
            return true;
        }
        return false;
    }

    public function hasPendingTpaRequest(Player $player): bool
    {
        return isset($this->tpaRequests[$player->getName()]);
    }

    /**
     * Checks if the TPA request has expired
     *
     * This function verifies if the TPA request for the specified player has expired based on the configured expiration time
     *
     * @param  Player $to The player who is receiving the TPA request
     * @return void
     */
    private function checkExpiredTpaRequest(Player $to): void
    {
        $name = $to->getName();
        if (isset($this->tpaRequests[$name])) {
            $request = $this->tpaRequests[$name];
            $timeElapsed = time() - $request["time"];

            if ($timeElapsed >= $this->expirationTime) {
                unset($this->tpaRequests[$name]);
                if ($to->isConnected()) {
                    $to->sendMessage($this->plugin->getConfig()->get("expired_tpa"));
                }

                $senderName = $request["from"];
                $sender = $to->getServer()->getPlayerExact($senderName);
                if ($sender instanceof Player && $sender->isConnected()) {
                    $sender->sendMessage($this->plugin->getConfig()->get("expired_tpa"));
                }
            }
        }
    }
}
