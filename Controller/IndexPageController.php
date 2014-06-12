<?php

namespace Symfony\Bundle\LogReaderBundle\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use LogReader\LogReaderBundle\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use LogReader\LogReaderBundle\Reader\FileReader;


class IndexPageController extends Controller
{
    public function indexAction()
    {

        /** @var FileReader $fileReader */
        $fileReader = $this->get('logreader.reader.filereader');
        $file = $this->container->getParameter('log_file_path');
        $fileName = $this->container->getParameter('log_file');

        $logCollection = $fileReader->load($file);
        $this->insert($logCollection);

        $params = $this->get('request')->query->all();

        if ($params != null){
            $logsColl = $this->readDatabase($fileName, null, $params);
        } else {
            $logsColl = $this->readDatabase($fileName, null, null);
        }

        return $this->render('LogReaderBundle:Default:index.html.twig', array(
            'title' => $fileName." - stats",
            'collection' => $logsColl)
        );
    }


    /**
     *
     */
    public function sortAction($id)
    {
        $fileName = $this->container->getParameter('log_file');

        $params = $this->get('request')->query->all();
        $logsColl = $this->readDatabase($fileName, $id, $params);

        return $this->render('LogReaderBundle:Default:index.html.twig', array(
                'title' => $fileName." - stats",
                'collection' => $logsColl)
        );
    }
    
    /**
     *
     */
    private function insert($logCollection)
    {
        /** @var ManagerRegistry $mongodb */
        $mongodb = $this->get('doctrine_mongodb');
        $dm = $mongodb->getManager();
        foreach($logCollection as $log) {
            $dm->persist($log);
        }
        $dm->flush();
    }


    /**
     *
     */
    private function readDatabase($fileName, $sortMethod = null, $params = null)
    {
        /** @var ManagerRegistry $mongodb */
        $mongodb = $this->get('doctrine_mongodb');
        $dm = $mongodb->getManager();

        /** @var $repository LogRepository */
        $repository = $dm->getRepository('LogReaderBundle:Log');

        $rep = $repository->findSortedAndFiltered($fileName, $sortMethod, $params);

        return $rep;
    }



}
