<?php

namespace App\Utils\Router;

use Exception;

class Router
{
    public static function buildRoutes()
    {
        define('TEMPLATES_DIR', __DIR__ . '/../../../templates/');

        $routes = [
            '/drivers' => [
                'GET' => 'App\Controllers\DriverController@show',
            ]
        ];

        $path = $_SERVER['PATH_INFO'] ?? '/';
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

        try {
            if (!array_key_exists($path, $routes)) {
                throw new Exception("La page correspondant à l'URL `$path` n'existe pas, corrigez le tableau des routes ou l'URL dans votre barre d'adresse");
            }

            $route = $routes[$path];
            [$className, $methodName] = self::getControllerForRoute($route, $method);

            if (!class_exists($className)) {
                throw new Exception("La classe <strong>$className</strong> n'existe pas et ne peut donc pas répondre à cette route ! Vous devriez construire cette classe ou alors corriger vos routes !");
            }

            $controller = new $className();

            if (!method_exists($controller, $methodName)) {
                throw new Exception("La classe <strong>$className</strong> n'a aucune méthode <strong>$methodName</strong> ! Vous devriez créer cette méthode ou corriger vos routes !");
            }

            $render = call_user_func([$controller, $methodName]);
            echo $render;
        } catch (Exception $e) {
            $errorMessage = $e->getMessage();
            require_once(TEMPLATES_DIR . "error.html.php");
        }
    }

    /**
     * Retourne un tableau contenant le nom de la classe et la méthode à appeler pour une route et une
     * méthode HTTP données
     *
     * @param array $route
     * @param string $httpMethod
     *
     * @return array
     */
    private static function getControllerForRoute(array $route, string $httpMethod = 'GET'): array
    {
        $controller = $route[$httpMethod];

        $className = substr($controller, 0, strpos($controller, '@'));
        $methodName = substr($controller, strpos($controller, '@') + 1);

        return [$className, $methodName];
    }
}
