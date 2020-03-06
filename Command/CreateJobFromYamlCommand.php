<?php

namespace QualityPress\Component\CPanel\Command;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

use QualityPress\Component\CPanel\Exception\RequiredParameterNotFoundException;
use QualityPress\Component\CPanel\CPanel;
use QualityPress\Component\CPanel\Service\ServiceInterface;
use QualityPress\Component\CPanel\Model\CronJob;
use QualityPress\Component\CPanel\Service\CronTab;

/**
 * This file is part of the QualityPress package.
 * 
 * (c) Jorge Vahldick
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CreateJobFromYamlCommand extends Command
{

    /**
     * {@inheridoc}
     */
    protected function configure()
    {
        $this
            ->setName('cpanel:cron:create-from-yaml')
            ->setDescription('Efetuar a configuracao e criacao de Job´s atraves de um arquivo yaml')
            ->addArgument(
                'filename',
                InputArgument::REQUIRED,
                'Qual o caminho do arquivo Yaml?'
            )
        ;
    }

    /**
     * {@inheridoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $file = $input->getArgument('filename');

        ### Verificar existência o arquivo
        if (false === file_exists($file)) {
            throw new \InvalidArgumentException('Arquivo inexistente');
        }

        ### Verificar se é um arquivo YAML
        if ('yml' != pathinfo($file, PATHINFO_EXTENSION))
        {
            throw new \InvalidArgumentException('Arquivo deve ser de extensão YML');
        }

        $data = Yaml::parse(file_get_contents($file));
        $this->validate($data);
        $this->create($data);

        $output->writeln('CRON Jobs adicionados com sucesso');
    }


    /**
     * Validação dos dados do arquivo YAML
     *
     * @param   array $data
     * @throws  RequiredParameterNotFoundException
     */
    protected function validate($data)
    {
        if (!isset($data['cpanel']['connection']) || !isset($data['cpanel']['cron']))
        {
            throw new \InvalidArgumentException('Dados do arquivo YAML estao incorretos.');
        }

        ### Validação dos dados necessários da conexão
        $r = array('host', 'user', 'pass');
        foreach ($data['cpanel']['connection'] as $k => $v) {
            if (in_array($k, $r)) {
                if (false !== $delete = array_search($k, $r)) {
                    unset($r[$delete]);
                }
            }
        }

        // Caso não tenha zerado o array, gera erro
        if (count($r) != 0) {
            throw new RequiredParameterNotFoundException(sprintf('Parametro %s obrigatorio nao encontrado', join(', ', $r)));
        }

        ### Validação da listagem de CRON Job´s
        $r = array('command', 'weekday', 'minute', 'hour', 'month', 'day');
        foreach ($data['cpanel']['cron'] as $k => $v) {
            if (!is_array($v)) {
                throw new \InvalidArgumentException('A lista de CRON Jobs deve ser um array');
            }

            if (0 != count(array_diff($r, array_keys($v)))) {
                throw new RequiredParameterNotFoundException(sprintf('Os parametros: "%s" do CRON Job nao foram localizados', join(', ', array_diff($r, array_keys($v)))));
            }
        }
    }

    /**
     * Percorrer a lista de jobs e efetuar a criação do mesmo no CPanel
     *
     * @param   array   $data
     * @throws  \QualityPress\Component\CPanel\Exception\BadApiFunctionCallException
     */
    protected function create($data)
    {
        $conn   = $data['cpanel']['connection'];
        $cpanel = CPanel::getInstance(
            $conn['host'],
            $conn['user'],
            $conn['pass'],
            (isset($conn['port'])) ? $conn['port'] : '2087',
            (isset($conn['protocol'])) ? $conn['protocol'] : 'https'
        );

        /* @var $cronTab CronTab */
        $cronTab    = $cpanel->getService(ServiceInterface::CRON_TAB);
        $cronJobs   = $data['cpanel']['cron'];

        foreach ($cronJobs as $job) {
            $item = new CronJob($job['command'], $job['weekday'], $job['month'], $job['day'], $job['hour'], $job['minute']);
            $cronTab->create($item);
        }
    }

}