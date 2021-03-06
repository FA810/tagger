<?php
require_once 'textminer/DatabaseBuddy.inc.php';

class Unmatched {

  private $unmatched;


  public function __construct($unmatched) {
    $this->unmatched = $unmatched;
  }

  public function logUnmatched() {
    foreach($this->unmatched AS $word) {
      if (!empty($word)) {
        if ($this->wordHasBeenLogged($word)) {
          $this->incrementWordCount($word);
        } else {
          $this->logNewWord($word);
        }
      }
    }
  }
  private function wordHasBeenLogged($word) {
    $sql = sprintf("SELECT count FROM unmatched WHERE name = '%s';", $word);
    $result = DatabaseBuddy::query($sql);
    $row = mysql_fetch_assoc($result);
    return (bool)$row;
  }
  private function logNewWord($word) {
    $sql = sprintf("INSERT INTO unmatched SET name = '%s', count = 1, created = CURRENT_TIMESTAMP", $word);
    $result = DatabaseBuddy::query($sql);
  }
  private function incrementWordCount($word) {
    $sql = sprintf("UPDATE unmatched SET count = count+1, updated = CURRENT_TIMESTAMP WHERE name ='%s';", $word);
    $result = DatabaseBuddy::query($sql);
  }

}

