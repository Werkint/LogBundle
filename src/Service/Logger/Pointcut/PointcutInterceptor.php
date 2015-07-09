<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Pointcut;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use Metadata\MetadataFactoryInterface;
use Metadata\MethodMetadata;
use Monolog\Logger;
use Werkint\Bundle\LogBundle\Service\Logger\DoctrineHandler;
use Werkint\Bundle\LogBundle\Service\Logger\LoggerAwareInterface;
use Werkint\Bundle\LogBundle\Service\Logger\Metadata\MethodMetadata as LoggerMethodMetadata;

/**
 * TODO: write "LoggerInterceptor" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class PointcutInterceptor implements
    MethodInterceptorInterface
{
    protected $metadataFactory;
    protected $logger;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     * @param Logger                   $logger
     */
    public function __construct(
        MetadataFactoryInterface $metadataFactory,
        Logger $logger
    ) {
        $this->metadataFactory = $metadataFactory;
        $this->logger = $logger;
    }

    /**
     * @param string $class
     * @param string $method
     * @throws \Exception
     * @return LoggerMethodMetadata|null
     */
    protected function findMethodMetadata($class, $method)
    {
        $metadata = $this->metadataFactory->getMetadataForClass($class);
        foreach ($metadata->methodMetadata as $methodMetadata) {
            /** @var MethodMetadata $methodMetadata */
            if ($methodMetadata->name !== $method) {
                continue;
            }

            if ($methodMetadata instanceof LoggerMethodMetadata) {
                return $methodMetadata;
            }
            break;
        }

        throw new \Exception('Wrong class specified: ' . $class);
    }

    /**
     * {@inheritdoc}
     */
    public function intercept(MethodInvocation $invocation)
    {
        $metadata = $this->findMethodMetadata(
            get_class($invocation->object),
            $invocation->reflection->name
        );
        $object = $invocation->object;
        /** @var LoggerAwareInterface $object */

        $targetObject = null;
        foreach ($invocation->reflection->getParameters() as $parameter) {
            if ($parameter->name === $metadata->getArgument()) {
                $targetObject = $invocation->arguments[$parameter->getPosition()];
                break;
            }
        }

        if ($targetObject) {
            $this->logger->pushProcessor(function ($record) use (&$targetObject) {
                $record['extra'][DoctrineHandler::OBJECT_KEY] = $targetObject;
                return $record;
            });
        }

        $object->setLogger($this->logger);
        $ret = $invocation->proceed();

        if ($targetObject) {
            $this->logger->popProcessor();
        }
        return $ret;
    }
} 