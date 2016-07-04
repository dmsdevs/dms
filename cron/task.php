<?php
namespace OCA\DeadManSwitch\Cron;

use \OCP\AppFramework\App;
use OCA\DeadManSwitch\Service\NoteService;
use OCA\DeadManSwitch\Files\File;
use OCA\DeadManSwitch\Files\Filesystem;
use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCA\DeadManSwitch\Db\Note;
use OCA\DeadManSwitch\Db\NoteMapper;
use \OCP\AppFramework\Db\Entity;
use OCP\IDb;
use OCP\AppFramework\Db\Mapper;
use OCP\IDBConnection;
use Exception;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;

    
class SomeTask {


    private $mapper;

    public static function run() {

		
		$userFolder = \OC::$server->getWebRoot();
        //$servername = $_SERVER['SERVER_NAME'];
        


        //not working, returns localhost
        //$servername = \OC::$server->query('\OCP\Util')->getServerHostName();   
        //$servername = \OC::$server->getRequest()->getServerHost();
                
        $servernames = \OC::$server->getConfig()->getSystemValue('trusted_domains', array());
        $servername = array_values($servernames)[0]; 
        
		$app = new App('deadmanswitch');
		$container = $app->getContainer();
		$mapper = $container->query(
				'OCA\DeadManSwitch\Db\NoteMapper'
		);

		date_default_timezone_get();
		$time = date("Y-m-d");
		
        //find notes that are due to today
		$noteList = $mapper->findDates($time);


		if (is_array($noteList)) {
			
			foreach($noteList as $t_note) {

                if(!($t_note->sent)){

                    

                    
                    $userFolder = \OC::$server->getUserFolder($t_note->userId);
                    $storage = $userFolder->getStorage();
                    $fullPath = $storage->getLocalFile($t_note->attachment);
                    $fullPath = str_replace("//", "/files/", $fullPath);
				
                    include('mail.php');	//workaround for phpmailer
				
                    $mail->From = "deadmanswitch@$servername";
                    $mail->FromName = "DeadManSwitch";
                    $mail->ReturnPath="no-reply@$servername";
                    $mail->addReplyTo("no-reply@$servername", 'no Reply');
                    $mail->AddAddress($t_note->target);
                    $mail->Subject = $t_note->title;
                    $mail->Body = $t_note->content;

                    $mail->AddAttachment($fullPath);
                    $mail->SMTPSecure = 'tls';
                    $mail->Send();

                    //make sure to update only if mail sent successfully
                    $t_note->setSent(true);
                    $mapper->update($t_note);
                    
                }
			}
		}
    }
}

$task = new SomeTask;
$task->run();



