<?php

namespace QualityPress\Component\CPanel\Service;

use QualityPress\Component\CPanel\Model\CronJob;
use QualityPress\Component\CPanel\Exception\BadApiFunctionCallException;

/**
 * This file is part of the QualityPress package.
 * 
 * (c) Jorge Vahldick
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CronTab extends Service
{

    /**
     * Localizar listagem de Job´s registrados no CPanel.
     *
     * @return array
     * @throws BadApiFunctionCallException
     */
    public function getList()
    {
        $result = json_decode($this->exec('listcron'))->cpanelresult;

        if (isset($result->error))
        {
            throw new BadApiFunctionCallException(sprintf('Erro "%s" na localização da lista de JOB´s.', $result->cpanelresult->error));
        }

        $cronList = array();
        foreach ($result->data as $cronObject)
        {
            if (isset($cronObject->command))
            {
                $cronList[] = $object = new CronJob(
                    $cronObject->command,
                    $cronObject->weekday,
                    $cronObject->month,
                    $cronObject->day,
                    $cronObject->hour,
                    $cronObject->minute
                );

                $object
                    ->setKey($cronObject->linekey)
                    ->setId($cronObject->count)
                ;
            }
        }

        return $cronList;
    }

    /**
     * Limpar toda a lista de Job´s registrados
     *
     * @return void
     * @throws BadApiFunctionCallException
     */
    public function clearList()
    {
        try {
            $data = $this->getList();
            if (count($data)) {
                for ($i = count($data); $i > 0; $i--) {
                    $this->delete($i);
                }
            }
        } catch (BadApiFunctionCallException $e) {
            throw $e;
        }
    }

    /**
     * Efetuar criação de uma nova tarefa.
     *
     * @param   CronJob $item
     * @return  mixed
     * @throws  BadApiFunctionCallException
     */
    public function create(CronJob $item)
    {
        $result = json_decode($this->exec('add_line', $item->toArray()))->cpanelresult;

        // Verificar erro
        if ($result->data[0]->status == 0) {
            throw new BadApiFunctionCallException('CRON Job inválido ou já existente');
        }

        return $result->data[0]->linekey;
    }

    /**
     * Editar alguma tarefa.
     *
     * @param   string  $key    Ordem ou linekey
     * @param   CronJob $item
     * @return  mixed
     * @throws  BadApiFunctionCallException
     */
    public function edit($key, CronJob $item)
    {
        $data               = $item->toArray();

        if (strlen($key) <= 2) {
            $data['commandnumber'] = $key;
        } else {
            $data['linekey'] = $key;
        }

        $result = json_decode($this->exec('edit_line', $data))->cpanelresult;

        // Verificar erro
        if ($result->data[0]->status == 0) {
            throw new BadApiFunctionCallException('CRON Job não encontrado');
        }

        return $result->data[0]->linekey;
    }

    /**
     * Remoção de um CronJob do CPanel.
     *
     * @param   int $id     Linha de remoção do CPanel
     * @throws  BadApiFunctionCallException
     *
     * @return void
     */
    public function delete($id)
    {
        $result = json_decode($this->exec('remove_line', array(
            'line' => $id
        )))->cpanelresult;

        // Verificar erro
        if ($result->data[0]->status == 0) {
            throw new BadApiFunctionCallException('CRON Job não encontrado ou já removido!');
        }
    }

    /**
     * {@inheridoc}
     */
    protected function getFunctionName()
    {
        return 'Cron';
    }

}