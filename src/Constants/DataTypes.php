<?php

namespace Coodde\Eliact\Constants;

class DataTypes
{
    // Basic types
    public const STRING = 'string';
    public const INT = 'int';
    public const FLOAT = 'float';
    public const BOOL = 'bool';

    // Times types
    public const DATE = 'date';
    public const YEAR = 'year';
    public const MONTH = 'month';
    public const DATETIME = 'datetime';
    public const TIME = 'time';

    // PII
    public const EMAIL = 'email';
    public const PHONE = 'phone';
    public const CREDIT_CARD = 'credit-card';

    // Geo
    public const ZIP = 'zip-code';
    public const COUNTRY = 'country-code';
    
    // Complicated types
    public const SAFE_STRING = 'safe-string';
    public const CHOICE = 'choice';

    // Other
    public const URL = 'url';
    public const UUID = 'uuid';
}  
