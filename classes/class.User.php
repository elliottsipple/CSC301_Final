<?php

class User {
    public $username;
    public $name;
    public $database;
    
    function __construct($userID, $database) {
        $sql = file_get_contents('sql/getUser.sql');
        $params = array(
            'username' => $_SESSION["userID"]
        );
        $statement = $database->prepare($sql);
        $statement->execute($params);
        $users = $statement->fetchAll(PDO::FETCH_ASSOC);
        $user = $users[0];
        
        $this->name = $user['name'];
        $this->username = $user['username'];
    }
}