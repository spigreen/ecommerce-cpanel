<?php

namespace QualityPress\Component\CPanel\Model;

/**
 * This file is part of the QualityPress package.
 * 
 * (c) Jorge Vahldick
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
class CronJob
{

    protected $id;
    protected $key;

    protected $command;
    protected $weekday;
    protected $month;
    protected $day;
    protected $hour;
    protected $minute;

    /**
     * Construtor.
     * Definir os dados padrões do Cron
     *
     * @param   string  $command
     * @param   string  $weekday
     * @param   string  $month
     * @param   string  $day
     * @param   string  $hour
     * @param   string  $minute
     */
    function __construct($command, $weekday, $month, $day, $hour, $minute)
    {
        $this->command  = $command;
        $this->weekday  = $weekday;
        $this->month    = $month;
        $this->day      = $day;
        $this->hour     = $hour;
        $this->minute   = $minute;
    }

    /**
     * TODO: Pensar em algo
     * @param $string
     */
    public static function createByString($string)
    {

    }

    public function toArray()
    {
        return array(
            'command'   => $this->getCommand(),
            'day'       => $this->getDay(),
            'minute'    => $this->getMinute(),
            'hour'      => $this->getHour(),
            'month'     => $this->getMonth(),
            'weekday'   => $this->getWeekday()
        );
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return CronItem
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param mixed $day
     * @return CronItem
     */
    public function setDay($day)
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMinute()
    {
        return $this->minute;
    }

    /**
     * @param mixed $minute
     * @return CronItem
     */
    public function setMinute($minute)
    {
        $this->minute = $minute;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getHour()
    {
        return $this->hour;
    }

    /**
     * @param mixed $hour
     * @return CronItem
     */
    public function setHour($hour)
    {
        $this->hour = $hour;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * @param mixed $weekday
     * @return CronItem
     */
    public function setWeekday($weekday)
    {
        $this->weekday = $weekday;
        return $this;
    }

    public function setMonth($month)
    {
        $this->month = $month;
        return $this;
    }

    public function getMonth()
    {
        return $this->month;
    }

    /**
     * @return mixed
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @param mixed $command
     * @return CronItem
     */
    public function setCommand($command)
    {
        $this->command = $command;
        return $this;
    }



}