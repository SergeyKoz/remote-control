<?php

namespace Control;

use Actions\CreateActions;
use DeviceCommands\CommandState;
use DeviceCommands\DeviceCommandInterface;
use PositionManager\PositionsManagerInterface;
use SwitchAllOnMacros;

interface ObserverInterface {
    public function UpdateCommandState(CommandState $commandState);
}

/**
 * Remote Control Service
 */
class RemoteControl implements ObserverInterface
{
    //apply macros
    use SwitchAllOnMacros;

    private PositionsManagerInterface $positionsManager;

    private ControlInterface $control;

    public function __construct(ControlInterface $control, PositionsManagerInterface $positionsManager)
    {
        $this->positionsManager = $positionsManager;
        $this->control = $control;
    }

    /**
     * Add new control item to the position
     * @param int $position
     * @param DeviceCommandInterface $command
     */
    public function add(int $position, DeviceCommandInterface $command)
    {
        try {
            [$actionOn, $actionOff] = CreateActions::fromCommand($command);
            $this->control->add($position, $actionOn, $actionOff);
            $this->positionsManager->setPosition(
                $position, new CommandState($position, $command));
        } catch (\Throwable $exception) {
            echo 'Add error:' . $exception->getMessage() . "\n";
        }
    }

    /**
     * Undo last action
     */
    public function undo()
    {
        try {
            $this->control->undo();
        } catch (\Throwable $exception) {
            echo 'Undo error:' . $exception->getMessage() . "\n";
        }
    }

    public function printDevicesList()
    {
        foreach ($this->positionsManager->getAllPositions() as $position => $state) {
            /** @var CommandState $state  */
            if (isset($state)) {
                echo "{$state->getCommand()}\n";
            }
        }
    }

    /**
     * Print commands list to console
     */
    public function printCommands()
    {
        foreach ($this->positionsManager->getAllPositions() as $position => $state) {
            /** @var CommandState $state  */
            if (isset($state)) {
                echo "$state\n";
            }
        }
    }

    /**
     * Switch On a devise
     *
     * @param int $position
     */
    public function performOn(int $position)
    {
        try {
            $this->control->performOn($position);
        } catch (\Throwable $error) {
            echo 'Perform On error:' . $error->getMessage() . "\n";
        }
    }

    /**
     * Switch Off a devise
     *
     * @param int $position
     */
    public function performOff(int $position)
    {
        try {
            $this->control->performOff($position);
        } catch (\Throwable $error) {
            echo 'Perform Off error:' . $error->getMessage() . "\n";
        }
    }

    /**
     * @param CommandState $commandState
     */
    public function UpdateCommandState(CommandState $commandState)
    {
        $position = $commandState->getPosition();
        $state = $commandState->getState();
        $this->positionsManager->setPosition($position,
            new CommandState($position, $this->positionsManager->getPosition($position)->getCommand(), $state));
    }
}
