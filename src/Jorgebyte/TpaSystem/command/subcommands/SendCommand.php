<?php

namespace Jorgebyte\TpaSystem\command\subcommands;

use CortexPE\Commando\args\RawStringArgument;
use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\TpaSystem\Main;
use Jorgebyte\TpaSystem\util\Sound;
use Jorgebyte\TpaSystem\util\SoundNames;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat;

class SendCommand extends BaseSubCommand
{
    public function __construct(private Main $plugin)
    {
        parent::__construct("send", "Send a tpa request to a player");
        $this->setPermission("tpasystem.command");
    }

    protected function prepare(): void
    {
        $this->registerArgument(0, new RawStringArgument("player"));
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        if (!isset($args["player"])) {
            $sender->sendMessage(TextFormat::RED . "Usage: /tpa send <player>");
            Sound::addSound($sender, SoundNames::BAD_TONE);
            return;
        }
        // Target (player2)
        $targetPlayerName = $args["player"];
        $targetPlayer = $sender->getServer()->getPlayerExact($targetPlayerName);

        if ($targetPlayer instanceof Player) {
            if ($this->plugin->getTpaManager()->hasPendingTpaRequest($targetPlayer)) {
                $sender->sendMessage(str_replace("{PLAYER}", $targetPlayerName, $this->plugin->getConfig()->get("already_tpa_pending")));
                Sound::addSound($sender, SoundNames::BAD_TONE);
                return;
            }
            $this->plugin->getTpaManager()->sendTpaRequest($sender, $targetPlayer);
            $sender->sendMessage(str_replace("{PLAYER}", $targetPlayerName, $this->plugin->getConfig()->get("tpa_send_request")));
            Sound::addSound($sender, SoundNames::GOOD_TONE);
            $targetPlayer->sendMessage(str_replace("{PLAYER}", $sender->getName(), $this->plugin->getConfig()->get("tpa_target_request")));
            Sound::addSound($targetPlayer, SoundNames::POP_TONE);
        } else {
            $sender->sendMessage(TextFormat::RED . "Player does not exist");
            Sound::addSound($sender, SoundNames::BAD_TONE);
        }
    }
}
