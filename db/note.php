<?php
namespace OCA\DeadManSwitch\Db;

use JsonSerializable;

use OCP\AppFramework\Db\Entity;

class Note extends Entity implements JsonSerializable {


//to do: change to protected if possible

    public $title;
    public $content;
    public $userId;
    public $target;
    public $time;
	public $timespan;
    public $trigger;
    public $attachment;
    public $sent;

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
			'target' => $this->target,
			'time' => $this->time,
			'timespan' => $this->timespan,
			'trigger' => $this->trigger,
			'attachment' => $this->attachment,
            'sent' => $this->sent
	];
    }
}
