<?php

namespace App\Traits;

trait Paginator
{
    protected $perPageMax = 1000;

    /**
     * Get the number of models to return per page.
     *
     * @return int
     */
    public function getPerPage(): int
    {
        $this->perPage = 24;
        $perPage = request('paginate', $this->perPage);

        if (strtolower($perPage) === 'all') {
            $perPage = $this->count();
        }

        return max(1, min($this->perPageMax, (int) $perPage));
    }

    /**
     * @param int $perPageMax
     */
    public function setPerPageMax(int $perPageMax): void
    {
        $this->perPageMax = $perPageMax;
    }
}
