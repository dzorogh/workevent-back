<?php

namespace App\DTOs;

use Illuminate\Pagination\AbstractPaginator;

readonly class PaginatorMetaDTO
{
    public function __construct(
        public int $current_page,
        public int $per_page,
        public int $last_page,
        public int $total,

    ) {}
}
