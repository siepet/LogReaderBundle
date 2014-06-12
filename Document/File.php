<?php

namespace Symfony\Bundle\LogReaderBundle\Document;

class File
{
    private $id;
    private $name;
    private $path;
    private $lines;

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setPath($path)
    {
        $this->path = $path;
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function getFullPath()
    {
        return $this->getPath()."/".$this->getName();
    }
}