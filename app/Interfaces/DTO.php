<?php

declare(strict_types=1);

namespace App\Interfaces;

interface DTO
{
	public function toArray(): array;
}