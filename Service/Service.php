<?php

namespace QualityPress\Component\CPanel\Service;

use QualityPress\Component\CPanel\CPanel;

/**
 * This file is part of the QualityPress package.
 * 
 * (c) Jorge Vahldick
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
abstract class Service implements ServiceInterface
{

    /**
     * API de conexão
     * @var \xmlapi
     */
    protected $connection;

    /**
     * Construtor.
     * Definir como base a conexão com o CPanel
     *
     * @param \xmlapi $connection
     */
    public function __construct(\xmlapi $connection)
    {
        $this->connection = $connection;
    }

    /**
     * Retornar a conexão com o CPanel
     * @return \xmlapi
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * Espécie de alias para execução de uma função.
     *
     * @param $method
     * @param array $args
     * @return mixed
     */
    protected function exec($method, $args = array())
    {
        return $this->getConnection()->api2_query($this->getConnection()->get_user(), $this->getFunctionName(), $method, $args);
    }

    /**
     * @return mixed
     */
    protected abstract function getFunctionName();

}