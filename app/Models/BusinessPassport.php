<?php

namespace App\Models;

use Database\Factories\BusinessPassportFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property int $user_id
 * @property string $business_name
 * @property string $business_type
 * @property string|null $target_customer
 * @property string|null $business_description
 * @property list<array{name: string, price: float, margin: float}>|null $products
 * @property list<string>|null $sales_channels
 * @property array{marketing_budget?: int, team_capacity?: int}|null $constraints
 * @property-read User $user
 * @property-read Collection<int, BusinessSnapshot> $snapshots
 * @property-read Collection<int, GrowthGoal> $growthGoals
 */
#[Fillable([
    'user_id',
    'business_name',
    'business_type',
    'target_customer',
    'business_description',
    'products',
    'sales_channels',
    'constraints',
])]
class BusinessPassport extends Model
{
    /** @use HasFactory<BusinessPassportFactory> */
    use HasFactory;

    protected $casts = [
        'products' => 'array',
        'sales_channels' => 'array',
        'constraints' => 'array',
    ];

    /** @return BelongsTo<User, $this> */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** @return HasMany<BusinessSnapshot, $this> */
    public function snapshots(): HasMany
    {
        return $this->hasMany(BusinessSnapshot::class);
    }

    /** @return HasMany<GrowthGoal, $this> */
    public function growthGoals(): HasMany
    {
        return $this->hasMany(GrowthGoal::class);
    }
}
