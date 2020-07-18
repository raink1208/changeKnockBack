<?php


namespace rain1208\changeKnockBack;


use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{
    /** @var Config */
    private $config;

    public function onEnable()
    {
        $this->config = new Config($this->getDataFolder()."config.yml",Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if (!$sender instanceof Player) return true;
        if (!$args[0]) return true;
        if (is_numeric($args[0])) {
            $name = $sender->getLevel()->getName();
            $sender->sendMessage("Knockback in the ".$name."has been changed to ".$args[0]);
            $this->config->set($sender->getLevel()->getName(),$args[0]);
            $this->config->save();
        }
        return true;
    }

    public function onDamage(EntityDamageByEntityEvent $event)
    {
        $kb = $this->config->get($event->getEntity()->getLevel()->getName()) ?? 0.5;
        $event->setKnockBack($kb);
    }
}
