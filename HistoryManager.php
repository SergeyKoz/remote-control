<?php

namespace HistoryManager;

use DeviceCommands\CommandState;
use SplStack;

interface HistoryManagerInterface
{
    public function addHistoryItem(CommandState $historyItem);

    public function undo(): CommandState;
}

/**
 * This is HistoryManager based on SplStack.
 *
 * @property integer $id
 * @property string $ref_id
 * @property string $mobile
 * @property integer $code
 * @property integer $created
 * @property integer $confirmed
 * @property integer $status
 */
class HistoryManager implements HistoryManagerInterface
{
    private SplStack $history;
    private int $historyLength;

    public function __construct(int $historyLength)
    {
        $this->historyLength = $historyLength;
        $this->history = new SplStack();
    }

    public function addHistoryItem(CommandState $historyItem)
    {
        $this->history->push($historyItem);
        if ($this->history->count() > $this->historyLength) {
            $this->history->shift();
        }
    }

    public function undo(): CommandState
    {
        return $this->history->pop();
    }
}
