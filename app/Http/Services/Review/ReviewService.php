<?php

namespace App\Http\Services\Review;

use App\Models\Review;

class ReviewService
{
    /**
     * Метод добавления отзыва
     * @param Review $review
     * @return bool
     */
    public function create(Review $review) : bool
    {
        if (!$review->save()) {
            return false;
        }
        return true;
    }

    /**
     * Метод обновления данных об отзыве
     * @param Review $review
     * @return bool
     */
    public function update(Review $review) : bool
    {
        if (!$review->save()) {
            return false;
        }
        return true;
    }
}
