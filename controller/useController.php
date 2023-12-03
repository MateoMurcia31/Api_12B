<?php
class UserController {
    private $_method;
    private $_complement;
    private $_data;
    const METHOD_GET = 'GET';
    const METHOD_POST = 'POST';
    const METHOD_PATCH = 'PATCH';
    const METHOD_DELETE = 'DELETE';
    const METHOD_OPTIONS = 'OPTIONS';
    public function __construct($method, $complement, $data) {
        $this->_method = $method;
        $this->_complement = $complement == null ? 0 : $complement;
        $this->_data = $data != 0 ? $data : "";
    }
    public function index() {
        switch ($this->_method) {
            case self::METHOD_GET:
                $user = UserModel::getUsers($this->_complement);
                $this->respondJson($user);
                break;
            case self::METHOD_POST:
                $createUser = UserModel::createUser($this->generateSalting());
                $this->respondJson(["response" => $createUser]);
                break;
            case self::METHOD_PATCH:
                $updateUser = UserModel::updateUsers($this->_data);
                $this->respondJson(["response" => $updateUser]);
                break;
            case self::METHOD_DELETE:
                $deleteUser = UserModel::deleteUsers($this->_data);
                $this->respondJson(["response" => $deleteUser]);
                break;
            case self::METHOD_OPTIONS:
                $changeStatus = UserModel::changeStatus($this->_data);
                $this->respondJson(["response" => $changeStatus]);
                break;
            default:
                $this->respondJson(["response" => "not found"]);
                break;
        }
    }
    private function respondJson($data) {
        echo json_encode($data, true);
    }
    private function generateSalting() {
        if (!empty($this->_data)) {
            $trimmedData = array_map('trim', $this->_data);
            $trimmedData['user_pss'] = password_hash($trimmedData['user_pss'], PASSWORD_DEFAULT);
            $identifier = str_replace("$", "ue3", crypt($trimmedData["user_mail"], 'u56'));
            $key = str_replace("$", "ue2023", crypt($trimmedData["user_mail"], '65ue'));
            $trimmedData['user_identifier'] = $identifier;
            $trimmedData['us_key'] = $key;
            return $trimmedData;
        }
    }
}
?>
