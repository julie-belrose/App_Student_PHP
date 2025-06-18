<?php

namespace src\model;

enum LogType: string
{
    case DEBUG = 'DEBUG';
    case WARN  = 'WARN';
    case ERR   = 'ERR';
}
