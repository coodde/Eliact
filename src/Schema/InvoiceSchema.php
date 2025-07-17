<?php

namespace Coodde\Eliact\Schemas;

use Coodde\Eliact\Constants\DataTypes;

class InvoiceSchema
{
    public static function schema(): array
    {
        return [
            'invoice_number' => DataTypes::STRING,
            'date'           => DataTypes::DATE,
            'amount'         => DataTypes::FLOAT,
            'currency'       => DataTypes::STRING,
            'seller'         => DataTypes::STRING,
            'buyer'          => DataTypes::STRING,
        ];
    }
}
