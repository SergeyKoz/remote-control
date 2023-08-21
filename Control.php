<?php

namespace Control;

use Actions\ActionInterface;
use DeviceCommands\ActionPairCommand;
use DeviceCommands\CommandState;
use HistoryManager\HistoryManagerInterface;
use InvalidArgumentException;
use PositionManager\PositionsManagerInterface;
use SplObjectStorage;

interface ControlInterface
{
    public function add(int $position, ActionInterface $actionOff, ActionInterface $actionOn);
    public function printCommands();
    public function undo();
    public function performOn(int $position);
    public function performOff(int $position);
}

class Control implements ControlInterface
{
    private PositionsManagerInterface $positionsManager;
    private HistoryManagerInterface $historyManager;
    private SplObjectStorage $observers;

    /**
     * RemoteControlDeprecated constructor.
     * @param HistoryManagerInterface $historyManager
     * @param PositionsManagerInterface $positionsManager
     * @param SplObjectStorage $observers
     */
    public function __construct(HistoryManagerInterface $historyManager, PositionsManagerInterface $positionsManager, SplObjectStorage $observers)
    {
        $this->positionsManager = $positionsManager;
        $this->historyManager = $historyManager;
        $this->observers = $observers;
    }

    /**
     * Set control item
     * @param int $position
     * @param ActionInterface $actionOn
     * @param ActionInterface $actionOff
     */
    public function add(int $position, ActionInterface $actionOn, ActionInterface $actionOff)
    {
        if ($this->positionsManager->isPositionEmpty($position)) {
            $this->positionsManager->setPosition(
                $position, new CommandState($position, new ActionPairCommand($actionOn, $actionOff)));
        } else {
            throw new InvalidArgumentException('Position ' . $position . ' is busy');
        }
    }

    /**
     * Print commands to console
     */
    public function printCommands()
    {
        foreach ($this->positionsManager->getAllPositions() as $position => $state) {
            if (isset($state)) {
                echo "$state\n";
            }
        }
    }

    /**
     * Undo last action
     */
    public function undo()
    {
        $historyItem = $this->historyManager->undo();
        $this->positionsManager->setPosition(
            $historyItem->getPosition(),
            $this->execute($historyItem->getState(), $historyItem)
        );
        $this->notify($historyItem);
    }

    /**
     * Switch On a devise
     *
     * @param int $position
     */
    public function performOn(int $position)
    {
        $currentState = $this->positionsManager->getPosition($position);
        $newState = $this->execute(true, $currentState);
        $this->positionsManager->setPosition($position, $newState);
        $this->historyManager->addHistoryItem($currentState);
    }

    /**
     * Switch Off a devise
     *
     * @param int $position
     */
    public function performOff(int $position)
    {
        $currentState = $this->positionsManager->getPosition($position);
        $newState = $this->execute(false, $currentState);
        $this->positionsManager->setPosition($position, $newState);
        $this->historyManager->addHistoryItem($currentState);
    }

    /**
     * Execute
     *
     * @param bool $state
     * @param CommandState $currentState
     * @return CommandState
     */
    private function execute(bool $state, CommandState $currentState)
    {
        $command = $currentState->getCommand();
        $state ? $command->on() : $command->off();
        $newState = new CommandState($currentState->getPosition(), $command, $state);
        $this->notify($newState);
        return $newState;
    }

    /**
     * @param CommandState $command
     */
    private function notify(CommandState $command)
    {
        foreach ($this->observers as $observer) {
            if ($observer instanceof ObserverInterface) {
                $observer->UpdateCommandState($command);
            }
        }
    }
}