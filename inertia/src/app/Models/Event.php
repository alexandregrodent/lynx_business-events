<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'start_date', 'end_date'];

    public function scopeUpcoming($query) {
        return $query->where('start_date', '>=', now());
    }

    public function scopeDateRange($query, $startDate, $endDate) {
        return $query->whereBetween('start_date', [$startDate, $endDate])
            ->whereBetween('end_date', [$startDate, $endDate]);
    }
}
