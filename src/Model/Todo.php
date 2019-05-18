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

  public function getTask($id){
    $sql = 'SELECT * FROM tasks WHERE id = ? ORDER BY id';
    $statement = $this->database->prepare($sql);
    $statement->bindValue(1, $id, PDO::PARAM_INT);
    $statement->execute();
    $task = $statement->fetch();

    if (empty($task)) {
      throw new ApiException(ApiException::TASK_NOT_FOUND, 404);
    }
    return $task;
  }
}
