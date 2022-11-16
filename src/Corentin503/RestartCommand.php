<?php

namespace Corentin503;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\world\sound\BlazeShootSound;

class RestartCommand extends Command
{
    public function __construct()
    {
        parent::__construct("restart", "Permet de redemarer le serveur", "/restart", ["redemarage"]);
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if ($sender->hasPermission("restart.use") or Server::getInstance()->isOp($sender->getName())) {
            foreach (Server::getInstance()->getOnlinePlayers() as $players) {
                $config = Main::getInstance()->getConfig();
                $players->sendTitle($config->get("restart-message"));
                Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(fn() => $players->getWorld()->addSound($players->getPosition()->asVector3(), new BlazeShootSound())), $config->get("restart-cooldown") * 20);
                Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(fn() => Main::getInstance()->getServer()->shutdown()), $config->get("restart-cooldown") * 20);
            }
        } else $sender->sendMessage("§cVous n'avez pas la permission");
    }
}