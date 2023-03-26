<?php

namespace App\handlers\commands;

use SergiX44\Nutgram\Nutgram;

interface Command
{
    public static function getName(): string;

    public static function getDescription(): string;
}