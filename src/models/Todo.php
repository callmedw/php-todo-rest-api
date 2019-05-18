<?php
namespace  App\Model;

class Todo {
  protected $database;

  public function __construct(\PDO $database) {
    $this->database = $database;
  }

  public function getCourses() {
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
}
