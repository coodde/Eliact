<?php

namespace Coodde\Eliact\Schemas;

use Coodde\Eliact\Constants\DataTypes;

class ContactSchema
{
    public static function schema(): array
    {
        return [
            'name'     => DataTypes::STRING,
            'email'    => DataTypes::EMAIL,
            'phone'    => DataTypes::PHONE,
            'company'  => DataTypes::STRING,
            'position' => DataTypes::STRING,
        ];
    }
}
