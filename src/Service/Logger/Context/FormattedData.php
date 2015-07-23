<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Context;

/**
 * TODO: write "FormattedData" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class FormattedData extends AbstractData
{
    const FORMAT_JSON = 'json';
    const FORMAT_XML = 'xml';

    protected $format;
    protected $data;

    /**
     * @param string $format
     * @param string $data
     */
    public function __construct(
        $format,
        $data
    ) {
        $this->format = $format;
        $this->data = $data;
    }

    /**
     * @inheritdoc
     */
    public function getType()
    {
        return 'formatted';
    }

    /**
     * @inheritdoc
     */
    protected function getData()
    {
        return [
            'format' => $this->format,
            'data'   => $this->data,
        ];
    }
}