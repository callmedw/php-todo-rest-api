<?php
namespace App\Exception;

class ApiException extends \Exception {
    const TASK_NOT_FOUND = 'Task Not Found';
    const TASK_INFO_REQUIRED = 'Required task data missing';
    const TASK_CREATION_FAILED = 'Unable to create task';
    const TASK_UPDATE_FAILED = 'Unable to update task';
    const TASK_DELETE_FAILED = 'Unable to delete task';


    public function __construct($message = '', $code = 400, \Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
