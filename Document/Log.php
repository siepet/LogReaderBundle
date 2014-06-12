<?php

namespace LogReader\LogReaderBundle\Document;

class Log
{
    /**  Default monolog's output format:
     *  "[%datetime%] %channel%.%level_name%: %message% %context% %extra%\n"
     */
    private $id;
    private $datetime;
    private $channel;
    private $levelName;
    private $message;
    private $context;
    private $extra;
    private $file;

    public function setDatetime(\DateTime $datetime)
    {
        $this->datetime = $datetime;
    }

    public function getDatetime()
    {
        return $this->datetime;
    }

    public function setChannel($channel)
    {
        $this->channel = $channel;
    }

    public function getChannel()
    {
        return $this->channel;
    }

    public function setLevelName($levelName)
    {
        $this->levelName = $levelName;
    }

    public function getLevelName()
    {
        return $this->levelName;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function getContext()
    {
        return $this->context;
    }

    public function setExtra($extra)
    {
        $this->extra = $extra;
    }

    public function getExtra()
    {
        return $this->extra;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function getFile()
    {
        return $this->file;
    }
} 