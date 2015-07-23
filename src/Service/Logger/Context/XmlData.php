<?php
namespace Werkint\Bundle\LogBundle\Service\Logger\Context;

/**
 * TODO: write "XmlData" info
 *
 * @author Bogdan Yurov <bogdan@yurov.me>
 */
class XmlData extends FormattedData
{
    public function __construct(
        $data
    ) {
        parent::__construct(static::FORMAT_XML, $data);
    }
}