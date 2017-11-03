<?php

namespace Ttskch\EsaCli\Exception;

class AuthenticationRequirementException extends RuntimeException
{
    public function __construct($message = 'Please `esa authenticate` in advance.')
    {
        parent::__construct($message);
    }
}
