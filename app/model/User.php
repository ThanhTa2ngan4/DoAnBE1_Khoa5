<?php
class User extends Database
{
    public function register($username, $password) {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare('INSERT INTO `users`(`username`, `password`) VALUES (?, ?)');
        $password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql->bind_param('ss', $username, $password);

        // 3 & 4
        return $sql->execute();
    }

    public function login($username, $password) {
        // 2. Tạo câu query
        $sql = parent::$connection->prepare('SELECT * FROM `users` WHERE `username`=?');        
        $sql->bind_param('s', $username);
        $user = parent::select($sql);

        if(count($user) > 0) {
            if(password_verify($password, $user[0]['password'])) {
                return $user[0];
            }     
        }
        
        // 3 & 4
        return false;
    }


    

}
