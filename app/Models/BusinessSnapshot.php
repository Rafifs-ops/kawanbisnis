<?php

namespace App\Models;

use Database\Factories\BusinessSnapshotFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $business_passport_id
 * @property Carbon $period_start
 * @property Carbon $period_end
 * @property float $revenue
 * @property int $total_orders
 * @property float $average_order_value
 * @property array{new?: int, returning?: int}|null $new_vs_returning_customers
 * @property list<array{name: string, revenue: float, orders: int}>|null $product_performances
 * @property string|null $raw_uploaded_file_path
 * @property-read BusinessPassport $businessPassport
 */
#[Fillable([
    'business_passport_id',
    'period_start',
    'period_end',
    'revenue',
    'total_orders',
    'average_order_value',
    'new_vs_returning_customers',
    'product_performances',
    'raw_uploaded_file_path',
])]
class BusinessSnapshot extends Model
{
    /** @use HasFactory<BusinessSnapshotFactory> */
    use HasFactory;

    protected $casts = [
        'period_start' => 'date',
        'period_end' => 'date',
        'revenue' => 'decimal:2',
        'average_order_value' => 'decimal:2',
        'new_vs_returning_customers' => 'array',
        'product_performances' => 'array',
    ];

    /** @return BelongsTo<BusinessPassport, $this> */
    public function businessPassport(): BelongsTo
    {
        return $this->belongsTo(BusinessPassport::class);
    }
}
