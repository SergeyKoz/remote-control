<?php

namespace Control;

use DeviceCommands\BathroomLightCommand;
use DeviceCommands\DoorCommand;
use DeviceCommands\GarageCommand;
use DeviceCommands\HeatingCommand;
use DeviceCommands\JacuzziCommand;
use DeviceCommands\JalousieCommand;
use DeviceCommands\KettleCommand;
use Devices\BathroomLight;
use Devices\Door;
use Devices\Garage;
use Devices\Heating;
use Devices\Jacuzzi;
use Devices\Jalousie;
use Devices\Kettle;
use HistoryManager\HistoryManager;
use PositionManager\PositionsManager;
use SplObjectStorage;

class OptimalControl
{
    public static function instance()
    {
        static $extendedController = null;

        if ($extendedController === null) {
            $bathroomLightCommand = new BathroomLightCommand(new BathroomLight());
            $JacuzziCommand = new JacuzziCommand(new Jacuzzi());
            $HeatingCommand = new HeatingCommand(new Heating());
            $GarageCommand = new GarageCommand(new Garage());
            $JalousieCommand = new JalousieCommand(new Jalousie());
            $KettleCommand = new KettleCommand(new Kettle());
            $doorCommand = new DoorCommand(new Door());

            $observers = new SplObjectStorage();

            $control = new Control(new HistoryManager(8), new PositionsManager(7), $observers);

            $extendedController = new RemoteControl($control, new PositionsManager(7));
            $observers->attach($extendedController);

            $extendedController->add(0, $bathroomLightCommand);
            $extendedController->add(1, $JacuzziCommand);
            $extendedController->add(2, $HeatingCommand);
            $extendedController->add(3, $GarageCommand);
            $extendedController->add(4, $JalousieCommand);
            $extendedController->add(5, $KettleCommand);
            $extendedController->add(6, $doorCommand);
        }
        return $extendedController;
    }
}
