<?php

namespace QualityPress\Component\CPanel;

use QualityPress\Component\CPanel\Factory\ServiceFactory;
use xmlapi;

/**
 * This file is part of the QualityPress package.
 * 
 * (c) Jorge Vahldick
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CPanel 
{

    /**
     * Instância do objeto
     * @var self
     */
    protected static $instance;

    /**
     * Conexão com CPanel
     * @var xmlapi
     */
    protected static $connection;

    /**
     * Localizar a fábrica de serviços
     * @var ServiceFactory
     */
    protected static $serviceFactory;

    /**
     * @param        $host
     * @param        $username
     * @param        $password
     * @param int    $port
     * @param string $protocol
     * @param bool   $debug
     *
     * @return CPanel
     */
    public static function getInstance($host, $username, $password, $port = 2087, $protocol = 'https', $debug = true)
    {
        if (!isset(self::$instance)) {
            $xmlapi = new xmlapi($host, $username, $password);
            $xmlapi->set_port($port);
            $xmlapi->set_user($username);
            $xmlapi->set_protocol($protocol);
            $xmlapi->set_output("json");
            $xmlapi->set_debug($debug);

            self::$instance         = new static;
            self::$connection       = $xmlapi;
            self::$serviceFactory   = new ServiceFactory();
        }

        return self::$instance;
    }

    /**
     * Localizar a instância
     *
     * @return xmlapi
     */
    public function getConnection()
    {
        return self::$connection;
    }

    /**
     * Localizar a fábrica de serviços
     *
     * @return ServiceFactory
     */
    protected function getServiceFactory()
    {
        return self::$serviceFactory;
    }

    /**
     * Localização do serviço
     *
     * @param   mixed $name     Serviço que deseja localizar
     * @return  \QualityPress\Component\Cpanel\Service\ServiceInterface
     */
    public function getService($name)
    {
        return $this->getServiceFactory()->get($name, array($this->getConnection()));
    }

}