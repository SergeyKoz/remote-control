<?php

namespace Devices;

class BathroomLight
{
    public function off()
    {
        echo 'BathroomLight off' . "\n";
    }

    public function on()
    {
        echo 'BathroomLight on' . "\n";
    }

    public function __toString()
    {
        return 'Bathroom Light';
    }
}

class Jacuzzi
{
    public function turnOn()
    {
        echo 'Jacuzzi on' . "\n";
    }

    public function turnOff()
    {
        echo 'Jacuzzi off' . "\n";
    }

    public function __toString()
    {
        return 'Jacuzzi';
    }
}

class Heating
{
    public function warmUp()
    {
        echo 'Heating warm up' . "\n";
    }

    public function warmDown()
    {
        echo 'Heating warm down' . "\n";
    }

    public function __toString()
    {
        return 'Heating';
    }
}

class Garage
{
    public function open()
    {
        echo 'Garage open' . "\n";
    }

    public function close()
    {
        echo 'Garage close' . "\n";
    }

    public function __toString()
    {
        return 'Garage';
    }
}

class Door
{
    public function open()
    {
        echo 'Door open' . "\n";
    }

    public function close()
    {
        echo 'Door close' . "\n";
    }

    public function __toString()
    {
        return 'Door';
    }
}

class Jalousie
{
    public function open()
    {
        echo 'Jalousie up' . "\n";
    }

    public function close()
    {
        echo 'Jalousie down' . "\n";
    }

    public function __toString()
    {
        return 'Jalousie';
    }
}

class Kettle
{
    public function on()
    {
        echo 'Kettle on' . "\n";
    }

    public function off()
    {
        echo 'Kettle off' . "\n";
    }

    public function __toString()
    {
        return 'Kettle';
    }
}
