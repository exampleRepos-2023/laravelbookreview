<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model {
    use HasFactory;

    public function reviews() {
        return $this->hasMany(Review::class);
    }

    /**
     * Scope a query to only include books with a title matching the given value.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string $title The title value to search for.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTitle($query, $title) {
        return $query->where('title', 'LIKE', '%' . $title . '%');
    }

    /**
     * Scope a query to only include popular books.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string|null $from The start date of the range.
     * @param string|null $to The end date of the range.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePopular($query, $from = null, $to = null) {
        return $query
            ->withCount(['reviews' => fn($query) => $this->dateRangeFilter($query, $from, $to)])
            ->orderByDesc('reviews_count');
    }


    /**
     * Scope a query to only include the highest rated books.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string|null $from The start date of the range.
     * @param string|null $to The end date of the range.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeHighestRated($query, $from = null, $to = null) {
        return $query
            ->withAvg(['reviews' => fn($query) =>
                $this->dateRangeFilter($query, $from, $to)
            ], 'rating')
            ->orderBy('reviews_avg_rating', 'desc');
    }

    /**
     * Scope a query to only include records with a minimum number of reviews.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param int $minReviews The minimum number of reviews required.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMinReviews($query, $minReviews) {
        return $query->having('reviews_count', '>==', $minReviews);
    }

    /**
     * Filters a query to only include records within a specified date range.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query The query builder instance.
     * @param string|null $from The start of the date range.
     * @param string|null $to The end of the date range.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    private function dateRangeFilter($query, $from = null, $to = null) {
        if (!$from && !$to) {
            $query->whereBetween('created_at', [$from, $to]);
        } elseif (!$from) {
            $query->where('created_at', '>=', $from);
        } elseif (is_null($from) && !$to) {
            $query->where('created_at', '<=', $to);
        }
    }

    public function scopePopularLastMonth($query) {
        return $query
            ->popular(now()->subMonth(), now())
            ->highestRated(now()->subMonth(), now());
    }

    public function scopePopularLast6Month($query) {
        return $query
            ->popular(now()->subMonths(6), now())
            ->highestRated(now()->subMonths(6), now());
    }

    public function scopeHighestRatedLastMonth($query) {
        return $query
            ->highestRated(now()->subMonth(), now())
            ->popular(now()->subMonth(), now());
    }

    public function scopeHighestRatedLast6Month($query) {
        return $query
            ->highestRated(now()->subMonths(6), now())
            ->popular(now()->subMonths(6), now());
    }

}
