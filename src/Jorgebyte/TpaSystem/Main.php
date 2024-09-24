<?php

namespace Jorgebyte\TpaSystem;

use CortexPE\Commando\PacketHooker;
use Jorgebyte\TpaSystem\command\TpaCommand;
use Jorgebyte\TpaSystem\manager\TpaManager;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase
{
    private TpaManager $tpaManager;

    public function onEnable(): void
    {
        if (!PacketHooker::isRegistered()) {
            PacketHooker::register($this);
        }
        $this->tpaManager = new TpaManager($this);
        $this->getServer()->getCommandMap()->register("TpaSystem", new TpaCommand($this));
        $this->saveDefaultConfig();
    }

    public function getTpaManager(): TpaManager
    {
        return $this->tpaManager;
    }
}
