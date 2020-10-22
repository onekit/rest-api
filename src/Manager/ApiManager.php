<?php

namespace App\Manager;

class ApiManager
{
    protected function handleError($violations): array
    {
        $messages = [];
        foreach ($violations as $constraint) {
            $prop = $constraint->getPropertyPath();
            $messages[$prop][] = $constraint->getMessage();
        }
        return $messages;
    }

}
