<?php

namespace App\Enums\Step;

enum StateEnum: int
{
    case UNCOMPLETED = 1;
    case COMPLETED = 2;

    public function toString(): string
    {
        return trans('enums/step.' . strtolower($this->name));
    }
}
