<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Context;

/**
 * TODO: write "ExceptionData" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class ExceptionData extends AbstractData
{
    /**
     * @var \Exception
     */
    protected $exception;

    public function __construct(
        \Exception $exception
    ) {
        $this->exception = $exception;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return 'exception';
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        $exception = $this->exception;

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