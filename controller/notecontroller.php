<?php
namespace OCA\DeadManSwitch\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;

use OCA\DeadManSwitch\Service\NoteService;
use OCA\DeadManSwitch\Files\File;
use OCA\DeadManSwitch\Files\Filesystem;



class NoteController extends Controller {



    private $service;
    private $userId;

    use Errors;

    public function __construct($AppName, IRequest $request,
                                NoteService $service, $UserId){
        parent::__construct($AppName, $request);
        $this->service = $service;
        $this->userId = $UserId;
    }

    /**
     * @NoAdminRequired
     */
    public function index() {
        return new DataResponse($this->service->findAll($this->userId));
    }

    /**
     * @NoAdminRequired
     *
     * @param int $id
     */
    public function show($id) {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->find($id, $this->userId);
        });
    }

    /**
     * @NoAdminRequired
     *
     * @param string $title
     * @param string $content
     */
    public function create($title, $content) {
        return $this->service->create($title, $content, $this->userId);
    }


    /**
     * @NoAdminRequired
     *
     * @param int $id
     * @param string $title
     * @param string $content
     */
    public function update($id, $title, $content, $target, $time, $timespan, $trigger, $attachment) {

        return $this->handleNotFound(function () use ($id, $title, $content, $target, $time, $timespan, $trigger, $attachment) {
                return $this->service->update($id, $title, $content, $target, $time, $timespan, $trigger, $attachment, $this->userId);
        });
    }


    /**
     * @NoAdminRequired
     *
     * @param int $id
     */
    public function destroy($id) {
        return $this->handleNotFound(function () use ($id) {
            return $this->service->delete($id, $this->userId);
        });
    }

}
