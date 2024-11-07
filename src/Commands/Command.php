<?php

namespace Src\Commands;

abstract class Command
{
    abstract public function execute(array $args);
}