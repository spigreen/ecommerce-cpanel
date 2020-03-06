<?php

// Incluir o arquivo de XML API do CPanel
require_once __DIR__ . '/libs/cpanel/xmlapi.php';

spl_autoload_register(function($class) {

    // Package namespace
    $ns = "QualityPress\\Component\\CPanel";

    // Prefixos
    $prefixes = array(
        "{$ns}" => array(
            __DIR__
        )
    );

    // Percorrer os itens para adicioná-los no autoload
    foreach ($prefixes as $prefix => $dirs)
    {
        // Verificar se a classe possui o namespace correspondente
        $prefixLen = strlen($prefix);

        if (substr($class, 0, $prefixLen) !== $prefix) {
            continue;
        }

        // Retirar o prefixo da classe
        $class = substr($class, $prefixLen);

        // Nome parcial do arquivo
        $part = str_replace('\\', DIRECTORY_SEPARATOR, $class) . '.php';

        // Ir nos diretórios e procurar as classes
        foreach ($dirs as $dir)
        {
            $dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);
            $file = $dir . DIRECTORY_SEPARATOR . $part;
            if (is_readable($file)) {
                require $file;
                return;
            }
        }
    }
});