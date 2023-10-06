<?php

namespace App\Enums\User;

enum RoleEnum: int
{
    case STUDENT = 1;
    case TEACHER = 2;

    public function toString() :string
    {
        return  trans('enums/user.'.strtolower($this->name));
    }
}
