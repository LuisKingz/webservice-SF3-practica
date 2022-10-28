<?php

namespace AppBundle\Services;

class Helpers {

    public $manager;

    public function __construct($manager) {
        $this->manager = $manager;
    }

    public function json($data) {
        //GetSetMethodNormalizer
        $normalizers = array(new \Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer());
        //JsonEncode
        $encoders = array("json" => new \Symfony\Component\Serializer\Encoder\JsonEncode);
        //Serializer
        $serializer = new \Symfony\Component\Serializer\Serializer($normalizers, $encoders);
        $json = $serializer->serialize($data, 'json');

        $response = new \Symfony\Component\HttpFoundation\Response();
        $response->setContent($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

}