<?php
namespace App\Model;
use App\Exception\ApiException;

class Task {
  protected $database;

  public function __construct(\PDO $database) {
    $this->database = $database;
  }

  public function getTasks() {
    $statement = $this->database->prepare(
      'SELECT * FROM tasks ORDER BY id'
    );
    $statement->execute();
    $tasks = $statement->fetchAll();
    if (empty($tasks)) {
      throw new ApiException(ApiException::TASK_NOT_FOUND, 404);
    }
    return $tasks;
  }

  public function getTask($id) {
    $statement = $this->database->prepare(
      'SELECT * FROM tasks WHERE id = :id'
    );
    $statement->bindParam('id', $id);
    $statement->execute();
    $task = $statement->fetch();
    if (empty($task)) {
      throw new ApiException(ApiException::TASK_NOT_FOUND, 404);
    }
    return $task;
  }

  public function createTask($data) {
    if (empty($data['task']) || empty($data['status'])) {
      throw new ApiException(ApiException::TASK_INFO_REQUIRED);
    }
    $statement = $this->database->prepare(
      'INSERT INTO tasks(task, status) VALUES(:task, :status)'
    );
    $statement->bindParam('task', $data['task']);
    $statement->bindParam('status', $data['status']);
    $statement->execute();
    if ($statement->rowCount() < 1) {
      throw new ApiException(ApiException::TASK_CREATION_FAILED);
    }
    return $this->getTask($this->database->lastInsertId());
  }

  public function updateTask($data) {
    if (empty($data['id']) || empty($data['task']) || empty($data['status'])) {
      throw new ApiException(ApiException::TASK_INFO_REQUIRED);
    }
    $statement = $this->database->prepare(
      'UPDATE tasks SET task=:task, status=:status WHERE id=:id'
    );
    $statement->bindParam('task', $data['task']);
    $statement->bindParam('status', $data['status']);
    $statement->bindParam('id', $data['id']);
    $statement->execute();
    if ($statement->rowCount()<1) {
      throw new ApiException(ApiException::TASK_UPDATE_FAILED);
    }
    return $this->getTask($data['id']);
  }

  public function deleteTask($id) {
    $this->getTask($id);
    $statement = $this->database->prepare(
      'DELETE FROM tasks WHERE id=:id'
    );
    $statement->bindParam('id', $id);
    $statement->execute();
    if ($statement->rowCount()<1) {
      throw new ApiException(ApiException::TASK_DELETE_FAILED);
    }
    return ['message' => 'The task was deleted'];
  }
}
