<?php
require_once("controller/routesController.php");
require_once("controller/userController.php");
require_once("controller/loginController.php");
require_once("model/userModel.php");
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Allow: GET, POST, OPTIONS, PUT, DELETE');
$rutasArray = explode("/", $_SERVER['REQUEST_URI']);
$endPoint = array_filter($rutasArray)[2];
if ($endPoint == 'login') {
    authenticateUser();
} else {
    handleRequest();
}
function authenticateUser() {
    if (isset($_SERVER['PHP_AUTH_USER']) && isset($_SERVER['PHP_AUTH_PW'])) {
        $identifier = $_SERVER['PHP_AUTH_USER'];
        $key = $_SERVER['PHP_AUTH_PW'];
        $users = UserModel::getUseAuth();
        $authenticationSuccessful = false;
        foreach ($users as $u) {
            if ("$identifier:$key" == "{$u['user_identifier']}:{$u['user_key']}") {
                $authenticationSuccessful = true;
                break;
            }
        }
        if ($authenticationSuccessful) {
            handleRequest();
        } else {
            respondWithError("USTED NO TIENE ACCESO");
        }
    } else {
        respondWithError("ERROR EN CREDENCIALES");
    }
}
function handleRequest() {
    $routes = new RoutesController();
    $routes->index();
}
function respondWithError($message) {
    $result["mensaje"] = $message;
    echo json_encode($result, true);
    return false;
}
?>
