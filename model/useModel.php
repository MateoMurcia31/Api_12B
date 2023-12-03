<?php 
require_once("ConDB.php");
class UserModel {
    static public function createUser($data) {
        $existingUserCount = self::getUserCount($data['user_mail']);
        if ($existingUserCount == 0) {
            $query = "INSERT INTO users (user_mail, user_pss, user_dateCreate, user_identifier, us_key, user_status) 
                      VALUES (:user_mail, :user_pss, :user_dateCreate, :user_identifier, :us_key, :user_status)";
            $status = 0; // 0 inactivo, 1 activo
            $stament = Connection::connection()->prepare($query);
            $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
            $stament->bindParam(":user_pss", $data['user_pss'], PDO::PARAM_STR);
            $stament->bindParam(":user_dateCreate", $data['user_dateCreate'], PDO::PARAM_STR);
            $stament->bindParam(":user_identifier", $data['user_identifier'], PDO::PARAM_STR);
            $stament->bindParam(":us_key", $data['us_key'], PDO::PARAM_STR);
            $stament->bindParam(":user_status", $status, PDO::PARAM_INT);
            $message = $stament->execute() ? "OK" : Connection::connection()->errorInfo();
            $stament->closeCursor();
        } else {
            $message = "Usuario ya está registrado";
        }
        return $message;
    }
    static private function getUserCount($mail) {
        $query = "SELECT COUNT(*) FROM users WHERE user_mail = :user_mail";
        $stament = Connection::connection()->prepare($query);
        $stament->bindParam(":user_mail", $mail, PDO::PARAM_STR);
        $stament->execute();
        $result = $stament->fetchColumn();
        return $result;
    }
    static public function getUsers($id) {
        $query = "SELECT user_id, user_mail, user_dateCreate FROM users";
        $query .= ($id > 0) ? " WHERE user_id = :user_id AND user_status = 1" : " WHERE user_status = 1";
        $stament = Connection::connection()->prepare($query);
        $stament->bindParam(":user_id", $id, PDO::PARAM_INT);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    static public function login($data) {
        $query = "SELECT user_id, user_identifier, user_key FROM users WHERE user_mail = :user_mail AND user_pss = :user_pss AND user_status = 1";
        $stament = Connection::connection()->prepare($query);
        $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
        $stament->bindParam(":user_pss", $data['user_pss'], PDO::PARAM_STR);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    static public function getActiveUsers() {
        $query = "SELECT user_identifier, user_key FROM users WHERE user_status = 1";
        $stament = Connection::connection()->prepare($query);
        $stament->execute();
        $result = $stament->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
    static public function updateUser($data) {
        $query = "UPDATE users SET user_mail = :user_mail, user_pss = :user_pss WHERE user_id = :user_id AND user_status = 1";
        $stament = Connection::connection()->prepare($query);
        $stament->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
        $stament->bindParam(":user_pss", $data['user_pss'], PDO::PARAM_STR);
        $message = $stament->execute() ? "Usuario actualizado" : Connection::connection()->errorInfo();
        $stament->closeCursor();
        return $message;
    }
    static public function deleteUser($data) {
        $query = "DELETE FROM users WHERE user_mail = :user_mail OR user_id = :user_id";
        $stament = Connection::connection()->prepare($query);
        $stament->bindParam(":user_mail", $data['user_mail'], PDO::PARAM_STR);
        $stament->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $message = $stament->execute() ? "Usuario eliminado" : Connection::connection()->errorInfo();
        $stament->closeCursor();
        return $message;
    }
    static public function changeStatus($data) {
        $query = "UPDATE users SET user_status = 1 WHERE user_id = :user_id";
        $stament = Connection::connection()->prepare($query);
        $stament->bindParam(":user_id", $data['user_id'], PDO::PARAM_INT);
        $message = $stament->execute() ? "Status actualizado" : Connection::connection()->errorInfo();
        $stament->closeCursor();
        return $message;
    }
}
?>
