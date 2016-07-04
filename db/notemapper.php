<?php
namespace OCA\DeadManSwitch\Db;

use OCP\IDb;
use OCP\AppFramework\Db\Mapper;

class NoteMapper extends Mapper {

    public function __construct(IDb $db) {
        parent::__construct($db, 'deadmanswitch_notes', '\OCA\DeadManSwitch\Db\Note');
    }

    public function find($id, $userId) {
        $sql = 'SELECT * FROM *PREFIX*deadmanswitch_notes WHERE id = ? AND user_id = ?';
	return $this->findEntity($sql, [$id, $userId]);
   }

    public function findAll($userId) {
        $sql = 'SELECT * FROM *PREFIX*deadmanswitch_notes WHERE user_id = ?';
        return $this->findEntities($sql, [$userId]);
    }

    public function findDate($time, $userId) {
        $sql = 'SELECT * FROM *PREFIX*deadmanswitch_notes WHERE time = ? AND user_id = ?';
        return $this->findEntity($sql, [$time, $userId]);
    }

    public function findDates($time) {
        $sql = 'SELECT * FROM *PREFIX*deadmanswitch_notes WHERE time <= ?';
        return $this->findEntities($sql, [$time]);
    }


}
