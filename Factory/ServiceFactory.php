<?php

namespace QualityPress\Component\CPanel\Factory;

use QualityPress\Component\CPanel\Service\ServiceInterface;

/**
 * This file is part of the QualityPress package.
 * 
 * (c) Jorge Vahldick
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class ServiceFactory 
{

    /**
     * Lista de namespaces para efetuar a busca do serviço.
     *
     * @var array
     */
    protected $namespaces = array(
        'QualityPress\\Component\\CPanel\\Service\\'
    );

    /**
     * Adicionar um namespace para efetuar uma pesquisa do serviço,
     * caso haja algum serviço fora do escopo do projeto.
     *
     * @param   string  $namespace
     */
    public function appendNamespace($namespace)
    {
        array_push($this->namespaces, $namespace);
    }

    /**
     * Retirar um namespace para efetuar uma pesquisa de serviço.
     * Este recurso pode ser utilizado para "sobreescrever" algum serviço local.
     *
     * @param   string  $namespace
     */
    public function prependNamespace($namespace)
    {
        array_unshift($this->namespaces, $namespace);
    }

    /**
     * Buscar um serviço CPanel existente.
     *
     * @param   mixed   $input  Serviço
     * @param   array   $args   Parâmetros a serem enviados ao construtor do Serviço
     *
     * @return ServiceInterface
     */
    public function get($input, array $args)
    {
        if ($input instanceof ServiceInterface) {
            return $input;
        }

        // Percorrer a listagem de namespaces para tentar localizar o banco
        foreach ($this->namespaces as $namespace) {
            $className = $namespace . ucfirst($input);

            if (!class_exists($className)) {
                continue;
            }

            $reflection = new \ReflectionClass($className);
            if (!$reflection->isSubclassOf('QualityPress\\Component\\CPanel\\Service\\ServiceInterface')) {
                throw new \InvalidArgumentException(sprintf('"%s" não é um serviço válido', $className));
            }

            return $reflection->newInstanceArgs($args);
        }
    }

}