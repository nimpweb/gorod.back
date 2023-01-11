<?php 

namespace core;

class Router {

    protected array $routes = [];
    public Request $request;
    public Response $response;

    public function __construct(Request $request, Response $response) {
        $this->request = $request;
        $this->response = $response;
    }

    public function get($path, $callback) {
        $this->routes['get'][$path] = $callback;
    }

    public function post($path, $callback) {
        $this->routes['post'][$path] = $callback;
    }

    public function resolve() {
        $path = $this->request->getPath();
        $method = $this->request->method();
        $func = $this->routes[$method][$path] ?? false;
        if ($func === false) { 
            $this->response->setStatusCode(404);
            return $this->renderView("_404");
         }

        if (is_string($func)) {
            return $this->renderView($func);
        }
        
        if (is_array($func)) {
            $controller = new $func[0]();
            Application::$app->controller = $controller;
            $controller->action = $func[1];
            $func[0] = $controller;

            foreach ($controller->getMiddlewares() as $middleware) $middleware->execute();
        }

        return call_user_func($func, $this->request, $this->response);
    }

    public function renderView($view, $params = []) {
        $layoutContent = $this->getLayout();
        $viewContent = $this->getView($view, $params);
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }
    
    public function renderContent($content) {
        $layoutContent = $this->getLayout();
        return str_replace("{{content}}", $content, $layoutContent);
    }

    
    protected function getLayout() {
        $layoutName = Application::$app->controller->layout ?? "default";
        ob_start();
        include_once Application::layoutPath() . "\\$layoutName.php";
        return ob_get_clean();
    }
    
    protected function getView($view, $params = []) {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::viewPath()."\\$view.php";
        return ob_get_clean();
    }

}