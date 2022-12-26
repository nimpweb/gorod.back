<?php

namespace core;

class View
{

    public function render($name)
    {
        $layoutName = Application::$app->layout ?? 'default';
        if (Application::$app->controller) $layoutName = Application::$app->controller->layout;
        $viewContent = $this->getView($name);
        ob_start();
        include_once Application::layoutPath() . "\\$layoutName.php";
        $layoutContent = ob_get_clean();
        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    private function getView($view, $params = [])
    {
        foreach ($params as $key => $value) {
            $$key = $value;
        }
        ob_start();
        include_once Application::viewPath() . "$view.php";
        return ob_get_clean();
    }
}
