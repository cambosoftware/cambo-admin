<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ActivityLogController extends Controller
{
    /**
     * Display a listing of activities.
     */
    public function index(Request $request)
    {
        $query = Activity::with(['causer', 'subject'])
            ->latest();

        // Filter by log name
        if ($request->filled('log')) {
            $query->inLog($request->log);
        }

        // Filter by event
        if ($request->filled('event')) {
            $query->forEvent($request->event);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->causedBy(User::find($request->user_id));
        }

        // Filter by date range
        if ($request->filled('from')) {
            $query->whereDate('created_at', '>=', $request->from);
        }
        if ($request->filled('to')) {
            $query->whereDate('created_at', '<=', $request->to);
        }

        // Search in description
        if ($request->filled('search')) {
            $query->where('description', 'like', '%' . $request->search . '%');
        }

        $activities = $query->paginate(20)->withQueryString();

        // Get available log names for filter
        $logNames = Activity::select('log_name')
            ->distinct()
            ->pluck('log_name');

        // Get available events for filter
        $events = Activity::select('event')
            ->whereNotNull('event')
            ->distinct()
            ->pluck('event');

        // Get users who have caused activities
        $users = User::whereIn('id', Activity::select('causer_id')
            ->where('causer_type', User::class)
            ->distinct())
            ->get(['id', 'name']);

        return Inertia::render('ActivityLog/Index', [
            'activities' => $activities,
            'logNames' => $logNames,
            'events' => $events,
            'users' => $users,
            'filters' => $request->only(['log', 'event', 'user_id', 'from', 'to', 'search']),
        ]);
    }

    /**
     * Display a single activity.
     */
    public function show(Activity $activity)
    {
        $activity->load(['causer', 'subject']);

        return Inertia::render('ActivityLog/Show', [
            'activity' => $activity,
        ]);
    }

    /**
     * Get activities for a specific model (API).
     */
    public function forSubject(Request $request, string $type, string $id)
    {
        $modelClass = $this->resolveModelClass($type);

        if (!$modelClass) {
            abort(404, 'Model type not found');
        }

        $subject = $modelClass::findOrFail($id);

        $activities = Activity::forSubject($subject)
            ->with('causer')
            ->latest()
            ->limit($request->get('limit', 10))
            ->get();

        return response()->json([
            'activities' => $activities,
        ]);
    }

    /**
     * Get activities for the authenticated user.
     */
    public function myActivities(Request $request)
    {
        $activities = Activity::causedBy($request->user())
            ->with('subject')
            ->latest()
            ->paginate(20);

        return Inertia::render('ActivityLog/MyActivities', [
            'activities' => $activities,
        ]);
    }

    /**
     * Clear old activities.
     */
    public function clear(Request $request)
    {
        $request->validate([
            'older_than_days' => 'required|integer|min:1',
        ]);

        $deleted = Activity::where('created_at', '<', now()->subDays($request->older_than_days))
            ->delete();

        return back()->with('success', "{$deleted} entrées de journal supprimées.");
    }

    /**
     * Resolve the model class from a type string.
     */
    protected function resolveModelClass(string $type): ?string
    {
        $map = [
            'user' => User::class,
            'role' => \App\Models\Role::class,
            // Add more models as needed
        ];

        return $map[$type] ?? null;
    }
}
