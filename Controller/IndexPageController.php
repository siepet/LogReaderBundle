<?php

namespace Symfony\Bundle\LogReaderBundle\Controller;

use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Bundle\LogReaderBundle\Repository\LogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Bundle\LogReaderBundle\Reader\FileReader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\Session\Session;

class IndexPageController extends Controller
{
    public function indexAction()
    {
        /** @var Session $session */
        $session = new Session();
        $session->start();

        /** @var FileReader $fileReader */
        $fileReader = $this->get('logreader.reader.filereader');
        $fileName = $this->container->getParameter('default_file');
        $data = $this->get('request')->request->get('logfile');
        if($data !== null){
            $fileName = $data;
            $session->set('logfile', $data);
        }
        if($session->has('logfile')){
            $fileName = $session->get('logfile');
        }
        $file = $this->container->getParameter('log_file_path');
        //$fileName = $this->container->getParameter('log_file');

        $logCollection = $fileReader->load($file);
        $this->insert($logCollection);

        $params = $this->get('request')->query->all();

        if ($params != null){
            $logsColl = $this->readDatabase($fileName, null, $params);
        } else {
            $logsColl = $this->readDatabase($fileName, null, null);
        }

        $channels = $this->getChannels();
        $finder = new Finder();
        $files = $finder->files()->in($this->container->getParameter('log_folder'));

        $logFiles = array();
        foreach($files as $file){
            $logFiles[] = basename($file);
        }
        return $this->render('LogReaderBundle:Default:index.html.twig', array(
            'title' => $fileName." - stats",
            'collection' => $logsColl,
            'files' => $logFiles,
            'channels' => $channels)
        );
    }


    /**
     *
     */
    public function sortAction($id)
    {
        //$fileName = $this->container->getParameter('log_file');
        $fileName = $this->container->getParameter('defualt_file');
        $session = new Session();
        $session->start();
        if($session->has('logfile')){
            $fileName = $session->get('logfile');
        }

        $params = $this->get('request')->query->all();
        $logsColl = $this->readDatabase($fileName, $id, $params);
    
        $finder = new Finder();
        $files = $finder->files->in($this->container->getParameter('log_folder'));
        $channels = $this->getChannels();
        $logFiles = array();
        foreach($files as $file){
            $logFiles[] = basename($file);
        }

        return $this->render('LogReaderBundle:Default:index.html.twig', array(
                'title' => $fileName." - stats",
                'collection' => $logsColl,
                'files' => $logFiles,
                'channels' => $channels)
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

    /**
    *   Function that retusn all channels found in the database
    */
    private function getChannels()
    {
        /** @var ManagerRegistery $mongodb */
        $mongodb = $this->get('doctrine_mongodb');
        $dm = $mongodb->getManager();

        /** @var LogRepository $repository */
        $repository = $dm->getRepository('LogReaderBundle:Log');
        $rep = $repository->findChannels();
        $channels = array();
        foreach($rep as $channel){
            $channels[] = $channel;
        }

        return $channels;
    }
}
