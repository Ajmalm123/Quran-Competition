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
        $zoneId = $request->input('zone_id');
        $admitStatus = $request->input('admit_status', 'all');

        $cacheKey = "approved_applicants_page_{$request->input('page', 1)}_perPage_{$perPage}_zoneId_{$zoneId}_admitStatus_{$admitStatus}";

        $applicants = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($perPage, $zoneId, $admitStatus) {
            $query = Application::where('status', 'Approved')
                ->with('zone:id,name')
                ->whereHas('zone', function ($subQuery) use ($zoneId) {
                    $subQuery->where('id', $zoneId);
                });

            if ($admitStatus !== 'all') {
                $query->where('admit_status', $admitStatus);
            }

            return $query->paginate($perPage);
        });

        return ApplicationResource::collection($applicants);
    }
}
