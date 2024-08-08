<?php

namespace JobMetric\Unit\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use JobMetric\PackageCore\Models\HasBooleanStatus;
use JobMetric\Translation\Contracts\TranslationContract;
use JobMetric\Translation\HasTranslation;

/**
 * JobMetric\Unit\Models\Unit
 *
 * @property mixed id
 * @property mixed type
 * @property mixed value
 * @property mixed status
 * @property mixed deleted_at
 * @property mixed created_at
 * @property mixed updated_at
 *
 * @property UnitRelation[] unitRelations
 *
 * @method static Builder ofType(string $type)
 * @method static find(int $unit_id)
 */
class Unit extends Model implements TranslationContract
{
    use HasFactory, SoftDeletes, HasBooleanStatus, HasTranslation;

    protected $fillable = [
        'type',
        'value',
        'status'
    ];

    protected $casts = [
        'type' => 'string',
        'value' => 'decimal:15',
        'status' => 'boolean'
    ];

    public function getTable()
    {
        return config('unit.tables.unit', parent::getTable());
    }

    public function translationAllowFields(): array
    {
        return [
            'name',
            'code',
            'position',
            'description',
        ];
    }

    public function unitRelations(): HasMany
    {
        return $this->hasMany(UnitRelation::class, 'unit_id', 'id');
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
}
