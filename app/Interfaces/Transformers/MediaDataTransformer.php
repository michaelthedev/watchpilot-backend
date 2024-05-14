<?php

declare(strict_types=1);

namespace App\Interfaces\Transformers;

use App\DTO\MovieDetail;
use App\DTO\TvDetail;

interface MediaDataTransformer
{
	public function transform(array $data): self;

	public function to(string $type): mixed;
}