<?php

use Control\Control;
use DeviceCommands\CommandState;
use PositionManager\PositionsManagerInterface;

/**
 * Macros example
 * @property Control $control;
 * @property PositionsManagerInterface $positionsManager;
 */
trait SwitchAllOnMacros
{
    public function SwitchAllOn()
    {
        $items = $this->positionsManager->getAllPositions();
        foreach ($items as $item) {
            /** @var $item CommandState */
            if (isset($item) && !$item->getState()) {
                $this->control->performOn($item->getPosition());
            }
        }
    }
}
