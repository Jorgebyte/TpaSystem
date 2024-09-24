<?php

namespace Jorgebyte\TpaSystem\command;

use CortexPE\Commando\BaseCommand;
use Jorgebyte\TpaSystem\command\subcommands\AcceptCommand;
use Jorgebyte\TpaSystem\command\subcommands\AllCommand;
use Jorgebyte\TpaSystem\command\subcommands\DenyCommand;
use Jorgebyte\TpaSystem\command\subcommands\SendCommand;
use Jorgebyte\TpaSystem\Main;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class TpaCommand extends BaseCommand
{
    public function __construct(private Main $plugin)
    {
        parent::__construct($plugin, "tpa", "TpaSystem Command");
        $this->setPermission("tpasystem.command");
    }

    protected function prepare(): void
    {
        $this->registerSubCommand(new SendCommand($this->plugin));
        $this->registerSubCommand(new AcceptCommand($this->plugin));
        $this->registerSubCommand(new DenyCommand($this->plugin));
        $this->registerSubCommand(new AllCommand($this->plugin));
    }

    public function onRun(CommandSender $sender, string $aliasUsed, array $args): void
    {
        $sender->sendMessage(TextFormat::RED . "Usage: /tpa <send|accept|deny>");

        if ($sender->hasPermission("tpasystem.command.all")) {
            $sender->sendMessage(TextFormat::YELLOW . "You can also use: /tpa all");
        }
    }

}
