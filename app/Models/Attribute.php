<?php

namespace App\Models;

use App\Traits\Filterable;
use App\Traits\Paginator;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Attribute extends Model
{
    use Filterable;
    use Paginator;

    protected $fillable = ['name', 'type', 'options'];

    protected $casts = [
        'options' => 'array',
    ];

    /**
     * Get the attribute values associated with this attribute.
     */
    public function values(): HasMany
    {
        return $this->hasMany(AttributeValue::class);
    }
}
