<?php
namespace Werkint\Bundle\LogBundle\Service\Logger;

use Doctrine\ORM\EntityManagerInterface;
use JMS\Serializer\JsonDeserializationVisitor;

/**
 * TODO: write "SerializerHandler" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class SerializerHandler
{
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function deserializeFromJson(
        JsonDeserializationVisitor $a,
        array $data,
        array $type
    ) {
        $repo = $this->entityManager->getRepository($data['class']);
        $object = $repo->find($data['id']);
        if (!$object instanceof LoggableObjectInterface) {
            throw new \Exception('Неправильный объект');
        }

        return $object;
    }
}