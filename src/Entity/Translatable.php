<?php

namespace App\Entity;

interface Translatable
{
    public function getTranslationType(): string;
    public function getValuesForTranslation(): array;

}