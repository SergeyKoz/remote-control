<?php

namespace PositionManager;

use DeviceCommands\CommandState;
use SplFixedArray;

interface PositionsManagerInterface
{
    /**
     * @param int $position
     * @param CommandState $commandState
     * @return mixed
     */
    public function setPosition(int $position, CommandState $commandState);

    /**
     * @param int $position
     * @return CommandState
     */
    public function getPosition(int $position) : CommandState;

    /**
     * @return iterable
     */
    public function getAllPositions(): Iterable;

    /**
     * @param int $position
     * @return bool
     */
    public function isPositionEmpty(int $position) : bool;
}

class PositionsManager implements PositionsManagerInterface
{
    private SplFixedArray $positions;

    public function __construct(int $positionsLength)
    {
        $this->positions = new SplFixedArray($positionsLength);
    }

    /**
     * @param int $position
     * @param CommandState $commandState
     */
    public function setPosition(int $position, CommandState $commandState)
    {
        $this->positions[$position] = $commandState;
    }

    /**
     * @param int $position
     * @return CommandState
     */
    public function getPosition(int $position): CommandState
    {
        if (!$this->isPositionEmpty($position)) {
            return $this->positions[$position];
        } else {
            throw new \InvalidArgumentException('Position ' . $position . ' is empty');
        }
    }

    /**
     * @return iterable
     */
    public function getAllPositions(): Iterable
    {
        return $this->positions;
    }

    /**
     * @param int $position
     * @return bool
     */
    public function isPositionEmpty(int $position): bool
    {
        return !isset($this->positions[$position]);
    }
}
