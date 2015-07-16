<?php

namespace EasyBib\Zf1\Controller\Plugin;

class QafooPlugin extends \Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(\Zend_Controller_Request_Abstract $request)
    {
        if (!class_exists('Tideways\Profiler')) {
            return;
        }

        $name = $this->createTransactionName($request);
        $method = $request->getMethod();

        \Tideways\Profiler::setTransactionName("{$method} {$name}");
    }

    /**
     * Use the route name, or build it based on /module/controller/action
     */
    private function createTransactionName($request)
    {
        $name = \Zend_Controller_Front::getInstance()->getRouter()->getCurrentRouteName();
        if ($name != 'default') {
            return "{$name} (route)";
        }

        $module = ucfirst($request->getModuleName());
        $controller = ucfirst($request->getControllerName());
        $action = $request->getActionName();

        $name = "{$controller}Controller::{$action}Action";
        if ('Default' != $module) {
            $name = "{$module}_{$name}";
        }

        return $name;
    }
}
