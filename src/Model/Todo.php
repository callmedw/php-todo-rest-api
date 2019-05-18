<?php
namespace App\Model;

class Todo {
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

  public function getTask($task_id) {
    $statement = $this->database->prepare(
      'SELECT * FROM tasks WHERE id=:id'
    );
    $statement->bindParam('id', $task_id);
    $statement->execute();
    $task = $statement->fetch();
    if (empty($tasks)) {
      throw new ApiException(ApiException::TASK_NOT_FOUND, 404);
    }
    return $task;
  }

  public function createTask($data) {
    if (empty($data['title']) || empty($data['url'])) {
      throw new ApiException(ApiException::TASK_INFO_REQUIRED);
    }
    $statement = $this->database->prepare(
    'INSERT INTO tasks(title, url) VALUES(:title, :url)'
    );
    $statement->bindParam('title', $data['title']);
    $statement->bindParam('url', $data['url']);
    $statement->execute();
    if ($statement->rowCount()<1) {
      throw new ApiException(ApiException::TASK_CREATION_FAILED);
    }
    return $this->getTask($this->database->lastInsertId());
  }

  public function updateTask($data) {
    if (empty($data['task_id']) || empty($data['title']) || empty($data['url'])) {
      throw new ApiException(ApiException::TASK_INFO_REQUIRED);
    }
    $statement = $this->database->prepare(
    'UPDATE tasks SET title=:title, url=:url WHERE id=:id'
    );
    $statement->bindParam('title', $data['title']);
    $statement->bindParam('url', $data['url']);
    $statement->bindParam('id', $data['task_id']);
    $statement->execute();
    if ($statement->rowCount()<1) {
      throw new ApiException(ApiException::TASK_UPDATE_FAILED);
    }
    return $this->getTask($data['task_id']);
  }

  public function deleteTask($task_id) {
    $this->getTask($task_id);
    $statement = $this->database->prepare(
      'DELETE FROM tasks WHERE id=:id'
    );
    $statement->bindParam('id', $task_id);
    $statement->execute();
    if ($statement->rowCount()<1) {
      throw new ApiException(ApiException::TASK_DELETE_FAILED);
    }
    return ['message' => 'The task was deleted'];
  }
}
