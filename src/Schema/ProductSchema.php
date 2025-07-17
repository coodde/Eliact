<?php

namespace Coodde\Eliact\Schemas;

use Coodde\Eliact\Constants\DataTypes;

class ProductSchema
{
    public static function schema(): array
    {
        return [
            'title'       => DataTypes::STRING,
            'price'       => DataTypes::FLOAT,
            'currency'    => DataTypes::STRING,
            'description' => DataTypes::STRING,
            'available'   => DataTypes::BOOL,
        ];
    }
}
