<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class UtilService{
    private $serializer;

    public function __construct(SerializerInterface $serializer){
        $this->serializer = $serializer;
    }

    public function serializer(mixed $objects, string $group){
        return $this->serializer->serialize($objects,'json',['groups'=>$group]);
    }

    public function getArray(iterable $data):array{
        $tab = [];
        foreach($data as $value)array_push($tab,$value);
        return $tab;
    }
}