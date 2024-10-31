<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 15);
        $zoneId = $request->input('zone_id');
        $admitStatus = $request->input('admit_status', 'all');

        $query = Application::where('status', 'Approved')
            ->with('zone:id,name')
            ->whereHas('zone', function ($subQuery) use ($zoneId) {
                $subQuery->where('id', $zoneId);
            });

        switch ($admitStatus) {
            case '1':
                $query->whereIn('admit_status', ['Admitted', 'Completed']);
                break;
            case '2':
                $query->where('admit_status', 'Admitted');
                break;
            case '3':
                $query->where('admit_status', 'Completed');
                break;
            default:
                $query->where('admit_status', 'Pending');
        }

        $applicants = $query->paginate($perPage);
        return ApplicationResource::collection($applicants);
    }

    public function show($application_id)
    {
        $cacheKey = "applicant_details_{$application_id}";

        $applicant = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($application_id) {
            return Application::where('application_id', $application_id)
                ->with('zone:id,name')
                ->first();
        });

        if (!$applicant) {
            return response()->json([
                'message' => 'Applicant not found'
            ], Response::HTTP_NOT_FOUND);
        }

        return new ApplicationResource($applicant);
    }
}
