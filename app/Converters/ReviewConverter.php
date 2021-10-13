<?php

namespace App\Converters;

use App\Models\Review;
use App\Models\User;
use App\Models\Role;

class ReviewConverter
{
    /**
     * Преобразовывает модель Review в массив
     *
     * @param Review $review
     * @return array
     */
    public static function oneToArray(Review $review): array
    {
        if($review->critic_user_id){
            $criticUser = User::find($review->critic_user_id);
            $review->criticUser = $criticUser->lastName.' '.$criticUser->firstName;
            $review->criticUserRole = Role::find($criticUser->role)->title;
        }

        $result = [
            'id'             => $review->id,
            'description'    => $review->description,
            'valueClient'    => $review->valueClient,
            'valueOther'     => $review->valueOther,
            'order_id'       => $review->order_id,
            'criticUser'     => $review->criticUser,
            'criticUserRole' => $review->criticUserRole,
            'created_at'     => $review->created_at,
            'updated_at'     => $review->updated_at,
        ];

        return $result;
    }

    /**
     * Преобразовывает коллекцю в массив
     *
     * @param $reviews
     * @return array
     */
    public static function manyToArray($reviews) : array
    {
        $items = [];

        foreach ($reviews as $review) {
            $items[] = self::oneToArray($review);
        }

        return ['items' => $items];
    }
}
