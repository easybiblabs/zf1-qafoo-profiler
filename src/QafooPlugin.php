<?php

namespace EasyBib\Zf1\Controller\Plugin;

use QafooLabs\Profiler;

class QafooPlugin extends \Zend_Controller_Plugin_Abstract
{
    public function routeShutdown(\Zend_Controller_Request_Abstract $request)
    {
        $name = $this->createTransactionName($request);
        $method = $request->getMethod();

        Profiler::setTransactionName("{$method} {$name}");
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
