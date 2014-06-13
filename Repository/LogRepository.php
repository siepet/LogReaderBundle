<?php

namespace Symfony\Bundle\LogReaderBundle\Repository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ODM\MongoDB\DocumentRepository;

class LogRepository extends DocumentRepository
{
    public function findSortedAndFiltered($fileName, $sort = null, $params = null)
    {
        $query = $this->createQueryBuilder()
            ->field('file')->equals($fileName)
            ->eagerCursor(true);
        if($params == null){
            if($sort != null){
                if($sort === 'date'){
                    $query = $query->sort('datetime', 'desc');
                } else {
                    $query = $query->sort($sort, 'asc');
                }
            }
        } else {
            $channel = $params['channel'];
            $level = $params['levelName'];
            $dateFrom = $params['dateFrom'];
            $dateFrom = new \MongoDate(strtotime($dateFrom));
            $dateTo = $params['dateTo'];
            $dateTo = new \MongoDate(strtotime($dateTo));

            if($channel === "null" && $level === "null"){
                $query = $query->field('datetime')->range($dateFrom, $dateTo);
            } elseif ($channel === "null"){
                $query = $query->field('levelName')->equals($level)
                    ->field('datetime')->range($dateFrom, $dateTo);
            } elseif ($level === "null"){
                $query = $query->field('channel')->equals($channel)
                    ->field('datetime')->range($dateFrom, $dateTo);
            } else {
                $query = $query->field('channel')->equals($channel)
                    ->field('levelName')->equals($level)
                    ->field('datetime')->range($dateFrom, $dateTo);
            }
            if($sort != null){
                $query = $query->sort($sort, 'asc');
            }
        }

        $rep = new ArrayCollection();

        $query = $query->getQuery()->execute();

        foreach($query as $cursor){
            $rep->add($cursor);
        }


        return $rep;
    }

    public function findChannels()
    {
        return $this->createQueryBuilder()->distinct('channel')->getQuery()->execute();
    }

}

