<?php

namespace Jorgebyte\TpaSystem\command\subcommands;

use CortexPE\Commando\BaseSubCommand;
use CortexPE\Commando\constraint\InGameRequiredConstraint;
use Jorgebyte\TpaSystem\Main;
use Jorgebyte\TpaSystem\util\Sound;
use Jorgebyte\TpaSystem\util\SoundNames;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\utils\TextFormat;

class AllCommand extends BaseSubCommand
{
    public function __construct(private Main $plugin)
    {
        parent::__construct("all", "have all players teleport like this to you");
        $this->setPermission("tpasystem.command.all");
    }

    protected function prepare(): void
    {
        $this->addConstraint(new InGameRequiredConstraint($this));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $players = $sender->getServer()->getOnlinePlayers();
        $cooldown = $this->plugin->getConfig()->get("cooldown_tpa_all");

        $sender->sendMessage(str_replace("{TIME}", $cooldown, $this->plugin->getConfig()->get("message_broadcast_tpall")));

        for ($i = $cooldown; $i > 0; $i--) {
            $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($players, $i): void {
                foreach ($players as $player) {
                    if ($player instanceof Player && $player->isOnline()) {
                        Sound::addSoundBroadcast([$player], SoundNames::ARROW_TONE);
                        $player->sendTitle(TextFormat::GOLD . $i . TextFormat::WHITE, "seconds remaining", 20, 10);
                    }
                }
            }), ($cooldown - $i) * 20);
        }

        if (!$sender instanceof Player) {
            return;
        }
        $this->plugin->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($players, $sender): void {
            foreach ($players as $player) {
                if ($player instanceof Player && $player->isOnline()) {
                    $player->teleport($sender->getPosition());
                }
            }
            $onlinePlayers = array_filter($players, fn ($p) => $p instanceof Player && $p->isOnline());
            if (!empty($onlinePlayers)) {
                Sound::addSoundBroadcast($onlinePlayers, SoundNames::GOOD_TONE_2);
            }
        }), $cooldown * 20);
    }
}
