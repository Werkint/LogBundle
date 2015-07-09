<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Metadata;

use Doctrine\Common\Annotations\Reader;
use Metadata\Driver\DriverInterface;
use Metadata\MergeableClassMetadata;
use Werkint\Bundle\LogBundle\Service\Logger\Annotation\LoggerAware;
use Werkint\Bundle\LogBundle\Service\Logger\LoggableObjectInterface;

/**
 * TODO: write "AnnotationDriver" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class AnnotationDriver implements
    DriverInterface
{
    const ANNOTATION_CLASS = LoggerAware::class;
    const OBJECT_CLASS = LoggableObjectInterface::class;

    protected $reader;

    /**
     * @param Reader $reader
     */
    public function __construct(Reader $reader)
    {
        $this->reader = $reader;
    }

    /**
     * {@inheritdoc}
     */
    public function loadMetadataForClass(\ReflectionClass $class)
    {
        $classMetadata = new MergeableClassMetadata($class->getName());

        foreach ($class->getMethods() as $method) {
            $annotation = $this->reader->getMethodAnnotation(
                $method,
                static::ANNOTATION_CLASS
            );

            if ($annotation instanceof LoggerAware) {
                $this->checkParameters($method, $annotation->getParameter());

                $propertyMetadata = new MethodMetadata($class->getName(), $method->getName());
                $propertyMetadata->setArgument($annotation->getParameter());
                $classMetadata->addMethodMetadata($propertyMetadata);
            }
        }

        return $classMetadata;
    }

    /**
     * @param \ReflectionMethod $method
     * @param string            $name
     * @throws \Exception
     */
    protected function checkParameters(\ReflectionMethod $method, $name)
    {
        if (!$name) {
            return;
        }
        foreach ($method->getParameters() as $parameter) {
            if ($parameter->name === $name) {
                if (!$parameter->getClass()) {
                    break;
                }
                if (!$parameter->getClass()->implementsInterface(static::OBJECT_CLASS)) {
                    break;
                }
                return;
            }
        }

        throw new \Exception('Wrong parameter typehint, or parameter not found: ' . $name .
            ' in class ' . $method->getDeclaringClass()->name
        );
    }
} 