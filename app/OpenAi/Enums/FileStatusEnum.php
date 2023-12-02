<?php

namespace App\OpenAi\Enums;

enum FileStatusEnum : string
{
    case processed = "processed";
    case error = "error";
    case uploaded = "uploaded";
}
