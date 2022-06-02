<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    public $timestamps = true;

    protected $fillable = [
        'type',
        'origin',
        'destination',
        'amount'
    ];

    protected $with = ['origin', 'destination'];

    /**
     * @return BelongsTo
     */
    public function originAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'origin');
    }

    /**
     * @return BelongsTo
     */
    public function destinationAccount(): BelongsTo
    {
        return $this->belongsTo(Account::class, 'destination');
    }
}
