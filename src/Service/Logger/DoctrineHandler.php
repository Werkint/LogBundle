<?php
namespace Werkint\Bundle\LogBundle\Service\Logger;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Werkint\Bundle\LogBundle\Entity\Log;

/**
 * Пишет лог в базу
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class DoctrineHandler extends AbstractProcessingHandler
{
    const MANAGER_NAME = 'log';
    const OBJECT_KEY = 'target_object';

    protected $doctrine;

    public function __construct(
        RegistryInterface $doctrine,
        $level = Logger::DEBUG,
        $bubble = true
    ) {
        $this->doctrine = $doctrine;

        parent::__construct($level, $bubble);
    }

    protected function getEntityManager()
    {
        if (!$this->doctrine->getEntityManager(static::MANAGER_NAME)->isOpen()) {
            $this->doctrine->resetEntityManager(static::MANAGER_NAME);
        }

        return $this->doctrine->getEntityManager(static::MANAGER_NAME);
    }

    protected function write(array $record)
    {
        $manager = $this->getEntityManager();

        $data = $record['extra'];

        if (!empty($record['context']['exception']) && $record['context']['exception'] instanceof \Exception) {
            $record['context']['exception'] = $this->formatException(
                $record['context']['exception']
            );
        }

        $log = new Log();
        $log->setMessage($record['message'])
            ->setLevel($record['level'])
            ->setExtraData($record['context'])
            ->setLoggedAt($record['datetime']);

        if (!empty($data[static::OBJECT_KEY])) {
            $object = $data[static::OBJECT_KEY];
            if (!$object instanceof LoggableObjectInterface) {
                throw new \Exception(sprintf('Wrong object class %s',
                    get_class($object)
                ));
            }
            $log->setObject($object);
        }

        $manager->persist($log);
        $manager->flush($log);
    }

    protected function formatException(\Exception $exception)
    {
        $messages = [];
        do {
            $messages[] = $exception->getMessage();
            $top = $exception;
        } while ($exception = $exception->getPrevious());

        return [
            'messages'   => $messages,
            'stacktrace' => explode("\n", $top->getTraceAsString()),
        ];
    }
}