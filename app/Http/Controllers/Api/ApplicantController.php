<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);

        $cacheKey = "approved_applicants_page_{$request->input('page', 1)}_perPage_{$perPage}";

        $applicants = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage) {
            return Application::where('status', 'Approved')
                ->with('zone:id,name')
                ->paginate($perPage);
        });

        return ApplicationResource::collection($applicants);
    }
}
