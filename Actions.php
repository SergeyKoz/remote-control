<?php

namespace Actions;

use DeviceCommands\DeviceCommandInterface;

interface ActionInterface
{
    public function execute();
}

class Action implements ActionInterface
{
    private \Closure $action;

    public function __construct(\Closure $action)
    {
        $this->action = $action;
    }

    public function execute()
    {
        $this->action->call($this);
    }
}

class CreateActions
{
    public static function fromCommand(DeviceCommandInterface $command)
    {
        $on = function () use ($command) {
            $command->on();
        };

        $off = function () use ($command) {
            $command->off();
        };
        return [new Action($on), new Action($off)];
    }
}