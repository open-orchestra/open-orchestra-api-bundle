<?php

namespace OpenOrchestra\BaseApi\Manager;

/**
 * Trait ContentTypeGeneratorTrait
 */
trait ContentTypeGeneratorTrait
{
    /**
     * @param string $format
     *
     * @return string
     */
    protected function generateContentType($format)
    {
        switch ($format) {
            case 'json':
                return 'application/json';
            case 'xml' :
                return 'text/xml';
            case 'yml':
                return 'application/yaml';
            default :
                return 'text/html';
        }
    }
}
