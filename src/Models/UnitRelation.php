<?php

namespace JobMetric\Unit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Pivot;
use JobMetric\Unit\Events\UnitableResourceEvent;

/**
 * JobMetric\Unit\Models\UnitRelation
 *
 * @property mixed unit_id
 * @property mixed unitable_type
 * @property mixed unitable_id
 * @property mixed type
 * @property mixed created_at
 *
 * @property Unit unit
 * @property mixed unitable
 * @property mixed unitable_resource
 *
 * @method static Builder ofType(string $collection)
 */
class UnitRelation extends Pivot
{
    use HasFactory;

    const UPDATED_AT = null;

    protected $fillable = [
        'unit_id',
        'unitable_type',
        'unitable_id',
        'type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'unit_id' => 'integer',
        'unitable_type' => 'string',
        'unitable_id' => 'integer',
        'type' => 'string'
    ];

    public function getTable()
    {
        return config('unit.tables.unit_relation', parent::getTable());
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function unitable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * Scope a query to only include categories of a given type.
     *
     * @param Builder $query
     * @param string $type
     *
     * @return Builder
     */
    public function scopeOfType(Builder $query, string $type): Builder
    {
        return $query->where('type', $type);
    }

    /**
     * Get the unitable resource attribute.
     */
    public function getUnitableResourceAttribute()
    {
        $event = new UnitableResourceEvent($this->unitable);
        event($event);

        return $event->resource;
    }
}
