<?php

namespace Symfony\Bundle\LogReaderBundle\Reader;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentManager;
use LogReader\LogReaderBundle\Document\Log;
use LogReader\LogReaderBundle\Document\File;


class FileReader
{

    private $lines = 0;
    private $mongodb;

    public function __construct(DocumentManager $mongodb)
    {
        $this->mongodb = $mongodb;
    }
    /**
     * Parses current log line and returns and object Log() based on that line
     */
    public function parse($line)
    {
        $pattern = '/\[(?P<date>.*)\] (?P<channel>\w+).(?P<levelName>\w+): (?P<message>.*[^ ]+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/';

        preg_match($pattern, $line, $data);

        $log = new Log();
        $log->setDatetime(\DateTime::createFromFormat('Y-m-d H:i:s', $data['date']));
        $log->setChannel($data['channel']);
        $log->setLevelName($data['levelName']);
        $log->setMessage($data['message']);
        $log->setContext(json_decode($data['context']), true);
        $log->setExtra(json_decode($data['extra']), true);

        return $log;

    }


    /**
     * Loads the logs from file and returns CollectionArray() with Log() objects made from lines
     */
    public function load($fileName)
    {
        $logCollection = new ArrayCollection();
        $fileHandle = fopen($fileName, "r");
        $logFileName = basename($fileName);
        $logFilePath = dirname($fileName);
        $currentFile = $this->mongodb->getRepository('LogReaderBundle:File')->findOneBy(array('name' => $logFileName));
        if($currentFile){
            $this->lines = $currentFile->getLines();
        } else {
            $currentFile = new File();
            $currentFile->setName($logFileName);
            $currentFile->setPath($logFilePath);
        }
        if($fileHandle){
            if($this->lines == 0){
                $logCollection = $this->loadLines($fileHandle, $logFileName);
            } else {
                for($i = 1; $i <= $this->lines; $i++){
                    $tmpLine = fgets($fileHandle);
                }
                $logCollection = $this->loadLines($fileHandle, $logFileName);
            }
        }

        $currentFile->setLines($this->lines);
        $this->mongodb->persist($currentFile);
        $this->mongodb->flush();

        fclose($fileHandle);

        return $logCollection;
    }

    private function loadLines($fileHandle, $fileName)
    {
        $logColl = new ArrayCollection();

        while ($line = fgets($fileHandle)) {
            if($line != "\n"){
                $this->lines++;
                $log = $this->parse($line);
                $log->setFile($fileName);
                $logColl->add($log);
            }
        }

        return $logColl;
    }
} 