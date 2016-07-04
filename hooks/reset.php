<?php
namespace OCA\DeadManSwitch\Hooks;
use \OCP\AppFramework\App;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCA\DeadManSwitch\Service\NoteService;


class Reset {
    
	
	public static function run($parameters) {
		
		$user=$parameters['uid'];
			
		$app = new App('deadmanswitch');
		$container = $app->getContainer();
		$mapper = $container->query(
				'OCA\DeadManSwitch\Db\NoteMapper'
		);
		$service = $container->query(
				'OCA\DeadManSwitch\Service\NoteService'
		);
		
		$noteList = $mapper->findAll($user);
		
		if (is_array($noteList)) {
			foreach($noteList as $t_note) {
		
				
				if($t_note->trigger=="login"){
					date_default_timezone_get();
					$date = date_create(date("Y/m/d"));
					date_modify($date, '+' . $t_note->timespan . ' days');
					$t_note->time=$date->format('Y-m-d');					
					$service->update($t_note->id, $t_note->title, $t_note->content, $t_note->target, $t_note->time, $t_note->timespan, $t_note->trigger, $t_note->attachment, $user);					
					
				}
			}
		}
		
		
		return true;
	}
}

