<?php

namespace App\Domains\LlmFunctions\Dto;

enum RoleTypeEnum : string
{

    case User = "user";
    case Function = "function";
    case System = "system";
    Case Assistant = "assistant";
}
