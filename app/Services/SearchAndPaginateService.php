<?php 

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;

class SearchAndPaginateService{

    //Builder Use for run query and Array use for when user search and it stor in array and then store a variable for peginations
    public function searchAndPaginate(Builder $query, array $searchParams = [], int $perPage = 15){

        foreach ($searchParams as $field => $value) {
            $query->where($field, 'LIKE', '%' . $value . '%');
        }

        $results = $query->paginate($perPage);

        return $results;
    }
}












?>