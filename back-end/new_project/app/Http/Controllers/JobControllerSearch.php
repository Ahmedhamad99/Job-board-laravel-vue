<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JobControllerSearch
{
    function search(Request $request)
    {
        // using query builder >> build a query to select all jobs that is accepted (in variable query)
        $query = Job::query()->where('status', 'accepted');

        if ($both = $request->query('titledesc')) {
            // group of conditions >> need mini query builder (use parenthesis to make query right)
            // use ($both) makes $both variable avail inside the closure (function)
            $query->where(function ($q) use ($both) {
                $q->where('title', 'like', '%' . $both . '%')
                    ->orWhere('description', 'like', '%' . $both . '%');
            });
        }

        if ($location = $request->query('location')) {
            $query->where('location', 'like', '%' . $location . '%');
        }

        if ($category = $request->query('category')) {
            // returns >> collection of matching categoryIds
            $categoryIds = Category::where('name', 'like', '%' . $category . '%')->pluck('id');
            // checks if the collection is not empty
            if ($categoryIds->isNotEmpty()) {
                $query->whereIn('category_id', $categoryIds);
            }
        }

        if ($salary_min = $request->query('salary_min')) {
            $query->where('salary_min', '>=', $salary_min);
        }

        if ($salary_max = $request->query('salary_max')) {
            $query->where('salary_max', '<=', $salary_max);
        }

        if ($from = $request->query('from_date')) {
            $date = Carbon::parse($from)->startOfDay();
            $query->where('created_at', '>', $date);
        }

        // default sort to created_at
        $sort = $request->query('sort', 'created_at');
        // default order to desc
        $order = $request->query('order', 'desc');
        // to add orderby and asc and decs to query
        $query->orderBy($sort, $order);

        // loads employer data of job (like populate)
        $jobs = $query->with(['employer', 'category'])->paginate(9);

        // response with json
        return response()->json([
            'success' => 'true',
            'data' => $jobs,
        ]);

        // example of query
        // SELECT * FROM jobs
        // WHERE status = 'accepted'
        // AND created_at > '2025-05-01 00:00:00'
        // AND (title LIKE '%developer%' OR description LIKE '%developer%')
        // AND location LIKE '%New York%'
        // ORDER BY created_at DESC
        // LIMIT 10 OFFSET 0
    }

    function getCategories() {
        $categories = Category::all();
        return response()->json([
            'success' => 'true',
            'data' => $categories,
        ]);
    }
}