<?php

namespace App\Api;

use JMS\Serializer\SerializerBuilder;

class Serializer
{
    public function serialize($data, $form = 'json')
    {
      $serializer = \JMS\Serializer\SerializerBuilder::create()
          ->setPropertyNamingStrategy(
              new \JMS\Serializer\Naming\SerializedNameAnnotationStrategy(
                  new \JMS\Serializer\Naming\IdenticalPropertyNamingStrategy()
              )
          )
          ->build();

        return $serializer->serialize($data, $form);
    }
}
