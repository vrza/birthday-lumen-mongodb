<?php declare(strict_types=1);

namespace App\Filters;

use App\Models\Person;

class PersonFilter extends Filter
{
    /**
     * Filter by person name.
     *
     * @param $name
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function name(string $name)
    {
        return $this->collection->where('name', $name);
    }
}
