<?php

namespace QualityPress\Component\CPanel\Command;

use QualityPress\Component\CPanel\CPanel;
use QualityPress\Component\CPanel\Model\CronJob;
use QualityPress\Component\CPanel\Service\ServiceInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Helper\QuestionHelper;

/**
 * This file is part of the QualityPress package.
 * 
 * (c) Jorge Vahldick
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CreateJobCommand extends Command
{

    protected function configure()
    {
        $this
            ->setName('cpanel:cron:create')
            ->setDescription('Inserir um CRON Job no CPanel')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /* @var $helper QuestionHelper */
        $helper = $this->getHelper('question');

        // Nome do host
        $qHost = new Question("Digite a URL de conexao: ");
        $qHost->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Nome do usuario
        $qUser = new Question("Qual o usuario de conexao: ");
        $qUser->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Senha
        $qPass = new Question("Qual a senha do usuario: ");
        $qPass->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Porta de conexao
        $qPort = new Question("Qual a porta de conexao? (Em branco para 2087)", 2087);

        // Protocolo de conexao
        $qProtocol = new Question("Qual o protocolo de conexao? (Em branco para https)", 'https');

        // Buscar o serviço de CPanel
        $host       = $helper->ask($input, $output, $qHost);
        $user       = $helper->ask($input, $output, $qUser);
        $pass       = $helper->ask($input, $output, $qPass);
        $port       = $helper->ask($input, $output, $qPort);
        $protocol   = $helper->ask($input, $output, $qProtocol);

        ### Criar instância de conexão com CPanel
        $conn = CPanel::getInstance($host, $user, $pass, $port, $protocol);

        // Comando da tarefa
        $qCommand = new Question("Qual o comando de execucao da tarefa: ");
        $qCommand->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Dados para inserir a tarefa
        $qWeekday = new Question("Dia da semana (weekday): ", '*');
        $qWeekday->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Dados para inserir a tarefa
        $qMonth = new Question("Digite o mes: ", '*');
        $qMonth->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Dados para inserir a tarefa
        $qDay = new Question("Digite o dia: ", '*');
        $qDay->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Dados para inserir a tarefa
        $qHour = new Question("Digite a hora: ", '*');
        $qHour->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Dados para inserir a tarefa
        $qMinute = new Question("Digite o minuto: ", '*');
        $qMinute->setValidator(function ($answer) {
            if ('' === trim($answer) || null === $answer) {
                throw new \RuntimeException("Esta informacao e obrigatoria!\n");
            }

            return $answer;
        });

        // Buscar dados da tarefa para inserção
        $command    = $helper->ask($input, $output, $qCommand);
        $weekday    = $helper->ask($input, $output, $qWeekday);
        $month      = $helper->ask($input, $output, $qMonth);
        $day        = $helper->ask($input, $output, $qDay);
        $hour       = $helper->ask($input, $output, $qHour);
        $minute     = $helper->ask($input, $output, $qMinute);

        // Criação da tarefa
        $cronJob = new CronJob($command, $weekday, $month, $day, $hour, $minute);
        $cronTab = $conn->getService(ServiceInterface::CRON_TAB);
        $cronTab->create($cronJob);

        $output->writeln("\n\n");
        $output->writeln('CRON Job adicionada com sucesso!');
    }

}