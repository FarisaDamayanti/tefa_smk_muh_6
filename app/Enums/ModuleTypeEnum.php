<?php

namespace App\Enums;

enum ModuleTypeEnum: string
{
    case PDF = 'pdf';
    case PPT = 'ppt';
    case WORD = 'word';
    case EXCEL = 'excel';
    case YOUTUBE = 'youtube';
    case OTHER = 'other';
    case EDITOR = 'editor';

    public static function linkType(): array
    {
        return [
            self::PDF->value,
            self::PPT->value,
            self::WORD->value,
            self::EXCEL->value,
            self::YOUTUBE->value,
            self::OTHER->value
        ];
    }
}
