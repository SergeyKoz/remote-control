<?php

define('__ROOT__', dirname(__FILE__));
require_once(__ROOT__ . '/Devices.php');
require_once(__ROOT__ . '/DeviceCommands.php');
require_once(__ROOT__ . '/HistoryManager.php');
require_once(__ROOT__ . '/PositionManager.php');
require_once(__ROOT__ . '/OptimalControlFactory.php');
require_once(__ROOT__ . '/MacrosControl.php');
require_once(__ROOT__ . '/Control.php');
require_once(__ROOT__ . '/RemoteControl.php');
require_once(__ROOT__ . '/Actions.php');

use Actions\CreateActions;
use Control\Control;
use Control\RemoteControl;
use Control\OptimalControl;
use DeviceCommands\BathroomLightCommand;
use DeviceCommands\CommandState;
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

//base usage create singleton
$control = OptimalControl::instance();
$control->performOn(5);
$control->performOn(0);
$control->performOff(5);
//Kettle on
//BathroomLight on
//Kettle off

$control->printCommands();
//0: Bathroom Light - On
//1: Jacuzzi - Off
//2: Heating - Off
//3: Garage - Off
//4: Jalousie - Off
//5: Kettle - Off
//6: Door - Off
$control->undo();
//Kettle on
$control->printDevicesList();
//Bathroom Light
//Jacuzzi
//Heating
//Garage
//Jalousie
//Kettle
//Door

//components
//devices
$bathroomLight = new BathroomLight();
$jacuzzi = new Jacuzzi();
$heating = new Heating();
$garage = new Garage();
$jalousie = new Jalousie();
$kettle = new Kettle();
$door = new Door();

// device commands
$bathroomLightCommand = new BathroomLightCommand($bathroomLight);
$JacuzziCommand = new JacuzziCommand($jacuzzi);
$HeatingCommand = new HeatingCommand($heating);
$GarageCommand = new GarageCommand($garage);
$JalousieCommand = new JalousieCommand($jalousie);
$KettleCommand = new KettleCommand($kettle);
$doorCommand = new DoorCommand($door);

//history manager based on SplStack
$historyManager = new HistoryManager(8);
$historyManager->addHistoryItem(new CommandState(0, $doorCommand, true));
$historyManager->undo();

//position manager based on SplFixedArray
$positionsManager = new PositionsManager(7);
$positionsManager->setPosition(1, new CommandState(0, $doorCommand, true));
$position = $positionsManager->getPosition(1);
$positions = $positionsManager->getAllPositions();

//create actions component
[$jacuzziActionOn, $jacuzziActionOff] = CreateActions::fromCommand($JacuzziCommand);
$jacuzziActionOn->execute();
$jacuzziActionOn->execute();
$jacuzziActionOff->execute();
//Jacuzzi on
//Jacuzzi on
//Jacuzzi off

//control component
$observers = new SplObjectStorage();
$controlHistoryManager = new HistoryManager(8);
$controlPositionsManager = new PositionsManager(7);

$control = new Control($controlHistoryManager, $controlPositionsManager, $observers);
$control->add(0, $jacuzziActionOn, $jacuzziActionOff);

$control->performOn(0);
$control->performOff(0);
//Jacuzzi on
//Jacuzzi off

$control->printCommands();
//0: Off

$control->undo();
$control->undo();
//Jacuzzi on
//Jacuzzi off

// remote control component
$remotePositionsManager = new PositionsManager(7);
$remoteControl = new RemoteControl($control, $remotePositionsManager);
$observers->attach($remoteControl);
$remoteControl->add(1, $doorCommand);
$remoteControl->add(1, $doorCommand);
//Add error:Position 1 is busy

$remoteControl->printCommands();
//1: Door - Off

$remoteControl->performOn(1);
$remoteControl->performOff(1);
//Door open
//Door close

$remoteControl->printCommands();
//1: Door - Off

$remoteControl->undo();
$remoteControl->undo();
$remoteControl->undo();
//Door open
//Door close
//Undo error:Can't pop from an empty datastructure

$remoteControl->performOn(5);
$remoteControl->performOff(5);
//Perform On error:Position 5 is empty
//Perform Off error:Position 5 is empty

$remoteControl->printCommands();
//1: Door - Off

$remoteControl->printDevicesList();
//Door

$remoteControl->SwitchAllOn();
//Door open
