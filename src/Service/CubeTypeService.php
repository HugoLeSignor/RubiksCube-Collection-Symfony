<?php

namespace App\Service;

class CubeTypeService
{
    public const CUBE_TYPES = [
        '2x2',
        '3x3',
        '4x4',
        '5x5',
        '6x6',
        '7x7',
        'Pyraminx',
        'Megaminx',
        'Skewb',
        'Square-1',
        'Mirror Cube',
        'Autre'
    ];

    public const DIFFICULTIES = [
        'Débutant',
        'Intermédiaire',
        'Expert'
    ];

    public function getCubeTypes(): array
    {
        return self::CUBE_TYPES;
    }

    public function getDifficulties(): array
    {
        return self::DIFFICULTIES;
    }

    public function isValidCubeType(string $type): bool
    {
        return in_array($type, self::CUBE_TYPES, true);
    }

    public function isValidDifficulty(string $difficulty): bool
    {
        return in_array($difficulty, self::DIFFICULTIES, true);
    }
}

