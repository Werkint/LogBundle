<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Context;

/**
 * TODO: write "JsonData" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class JsonData extends FormattedData
{
    public function __construct(
        $data
    ) {
        parent::__construct(static::FORMAT_JSON, $data);
    }
}