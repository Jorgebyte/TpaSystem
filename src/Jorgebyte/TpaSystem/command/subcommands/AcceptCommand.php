<?php

namespace Jorgebyte\TpaSystem\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\TpaSystem\Main;
use Jorgebyte\TpaSystem\util\Sound;
use Jorgebyte\TpaSystem\util\SoundNames;
use pocketmine\command\CommandSender;

class AcceptCommand extends BaseSubCommand
{
    public function __construct(private Main $plugin)
    {
        parent::__construct("accept", "Accept a tpa request from a player");
        $this->setPermission("tpasystem.command");
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if ($this->plugin->getTpaManager()->acceptTpaRequest($sender)) {
            $sender->sendMessage($this->plugin->getConfig()->get("tpa_accept_request"));
            Sound::addSound($sender, SoundNames::GOOD_TONE);
        } else {
            $sender->sendMessage($this->plugin->getConfig()->get("no_pending_tpa"));
            Sound::addSound($sender, SoundNames::BAD_TONE);
        }
    }
}
