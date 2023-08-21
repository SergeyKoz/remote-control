<?php

namespace DeviceCommands;

use Actions\ActionInterface;
use Devices\BathroomLight;
use Devices\Door;
use Devices\Garage;
use Devices\Heating;
use Devices\Jacuzzi;
use Devices\Jalousie;
use Devices\Kettle;

interface DeviceCommandInterface
{
    public function on();

    public function off();
}

class CommandState
{
    private bool $state;

    private int $position;

    private DeviceCommandInterface $command;

    public function __construct($position, $command, $state = false)
    {
        $this->state = $state;
        $this->command = $command;
        $this->position = $position;
    }

    public function getState()
    {
        return $this->state;
    }

    public function getCommand()
    {
        return $this->command;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function __toString()
    {
        return $this->position . ': ' .
            ((string)$this->command != '' ? (string)$this->command . ' - ' : '') .
            ($this->state ? 'On' : 'Off');
    }
}

abstract class AbstractCommand implements DeviceCommandInterface
{
    protected Object $device;

    public function __construct($device)
    {
        $this->device = $device;
    }

    abstract public function on();

    abstract public function off();

    public function __toString()
    {
        return (string)$this->device;
    }
}

class ActionPairCommand extends AbstractCommand
{
    private ActionInterface $actionOn;
    private ActionInterface $actionOff;

    public function __construct(ActionInterface $actionOn, ActionInterface $actionOff)
    {
        $this->actionOn = $actionOn;
        $this->actionOff = $actionOff;
    }

    public function on()
    {
        $this->actionOn->execute();
    }

    public function off()
    {
        $this->actionOff->execute();
    }

    public function __toString()
    {
        return '';
    }
}

/**
 * @property BathroomLight $device
 */
class BathroomLightCommand extends AbstractCommand
{
    public function on()
    {
        $this->device->on();
    }

    public function off()
    {
        $this->device->off();
    }
}

/**
 * @property Jacuzzi $device
 */
class JacuzziCommand extends AbstractCommand
{
    public function on()
    {
        $this->device->turnOn();
    }

    public function off()
    {
        $this->device->turnOff();
    }
}

/**
 * @property Heating $device
 */
class HeatingCommand extends AbstractCommand
{
    public function on()
    {
        $this->device->warmUp();
    }

    public function off()
    {
        $this->device->warmDown();
    }
}

/**
 * @property Garage $device
 */
class GarageCommand extends AbstractCommand
{
    public function on()
    {
        $this->device->open();
    }

    public function off()
    {
        $this->device->close();
    }
}

/**
 * @property Door $device
 */
class DoorCommand extends AbstractCommand
{
    public function on()
    {
        $this->device->open();
    }

    public function off()
    {
        $this->device->close();
    }
}

/**
 * @property Jalousie $device
 */
class JalousieCommand extends AbstractCommand
{
    public function on()
    {
        $this->device->open();
    }

    public function off()
    {
        $this->device->close();
    }
}

/**
 * @property Kettle $device
 */
class KettleCommand extends AbstractCommand
{
    public function on()
    {
        $this->device->on();
    }

    public function off()
    {
        $this->device->off();
    }
}
