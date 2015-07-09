<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Pointcut;

use JMS\AopBundle\Aop\PointcutInterface;
use Metadata\MetadataFactoryInterface;
use Werkint\Bundle\LogBundle\Service\Logger\LoggerAwareInterface;
use Werkint\Bundle\LogBundle\Service\Logger\Metadata\MethodMetadata;

/**
 * TODO: write "LoggerPointcut" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class LoggerPointcut implements PointcutInterface
{
    protected $metadataFactory;

    /**
     * @param MetadataFactoryInterface $metadataFactory
     */
    public function __construct(
        MetadataFactoryInterface $metadataFactory
    ) {
        $this->metadataFactory = $metadataFactory;
    }

    const LOGGER_CLASS = LoggerAwareInterface::class;

    /**
     * {@inheritdoc}
     */
    public function matchesClass(\ReflectionClass $class)
    {
        if (!$class->isSubclassOf(static::LOGGER_CLASS)) {
            return false;
        }
        $metadata = $this->metadataFactory->getMetadataForClass($class->getName());
        foreach ($metadata->methodMetadata as $methodMetadata) {
            if ($methodMetadata instanceof MethodMetadata) {
                return true;
            }
        }

        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function matchesMethod(\ReflectionMethod $method)
    {
        $metadata = $this->metadataFactory->getMetadataForClass($method->getDeclaringClass()->getName());
        foreach ($metadata->methodMetadata as $methodMetadata) {
            if ($methodMetadata instanceof MethodMetadata && $methodMetadata->name === $method->name) {
                return true;
            }
        }
        return false;
    }
}