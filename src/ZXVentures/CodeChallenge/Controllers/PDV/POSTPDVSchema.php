<?php

namespace ZXVentures\CodeChallenge\Controllers\PDV;

use ZXVentures\CodeChallenge\Controllers\AbstractSchema;

/**
 * Class POSTPDVValidator
 * @package ZXVentures\CodeChallenge\Controllers\PDV
 */
class POSTPDVSchema extends AbstractSchema
{
    /**
     * @return object|\StdClass
     */
    public function _getSchema()
    {
        return json_decode(
<<<'JSON'
{
    "$schema": "http://json-schema.org/draft-04/schema#",
    "properties": {
        "address": {
            "properties": {
                "coordinates": {
                    "items": {
                        "type": "number"
                    },
                    "maxItems": 2,
                    "minItems": 2,
                    "type": "array"
                },
                "type": {
                    "enum": [
                        "Point"
                    ],
                    "type": "string"
                }
            },
            "required": [
                "coordinates",
                "type"
            ],
            "type": "object"
        },
        "coverageArea": {
            "properties": {
                "coordinates": {
                    "items": {
                        "items": {
                            "items": {
                                "items": {
                                    "type": "number"
                                },
                                "maxItems": 2,
                                "minItems": 2,
                                "type": "array"
                            },
                            "minItems": 4,
                            "type": "array"
                        },
                        "minItems": 1,
                        "type": "array"
                    },
                    "minItems": 1,
                    "type": "array"
                },
                "type": {
                    "enum": [
                        "MultiPolygon"
                    ],
                    "type": "string"
                }
            },
            "required": [
                "coordinates",
                "type"
            ],
            "type": "object"
        },
        "document": {
            "type": "string"
        },
        "ownerName": {
            "maxLength": 255,
            "minLength": 1,
            "type": "string"
        },
        "tradingName": {
            "maxLength": 255,
            "minLength": 1,
            "type": "string"
        }
    },
    "required": [
        "tradingName",
        "ownerName",
        "document",
        "coverageArea",
        "address"
    ],
    "type": "object"
}
JSON
        );
    }
}
