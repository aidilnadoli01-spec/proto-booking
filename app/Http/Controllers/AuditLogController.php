<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'q' => 'nullable|string|max:100',
            'action' => 'nullable|string|max:80',
            'user_id' => 'nullable|exists:users,id',
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
        ]);

        $q = $validated['q'] ?? null;
        $action = $validated['action'] ?? null;
        $userId = $validated['user_id'] ?? null;
        $dateFrom = $validated['date_from'] ?? null;
        $dateTo = $validated['date_to'] ?? null;

        $query = AuditLog::query()
            ->with('user')
            ->when($action, fn ($qq) => $qq->where('action', $action))
            ->when($userId, fn ($qq) => $qq->where('user_id', $userId))
            ->when($dateFrom, fn ($qq) => $qq->whereDate('created_at', '>=', $dateFrom))
            ->when($dateTo, fn ($qq) => $qq->whereDate('created_at', '<=', $dateTo))
            ->when($q, function ($qq) use ($q) {
                $qq->where(function ($sub) use ($q) {
                    $sub->where('action', 'like', "%{$q}%")
                        ->orWhere('auditable_type', 'like', "%{$q}%")
                        ->orWhere('auditable_id', 'like', "%{$q}%")
                        ->orWhere('ip', 'like', "%{$q}%");
                });
            })
            ->latest();

        return view('audit.index', [
            'logs' => $query->paginate(20)->withQueryString(),
            'users' => User::orderBy('name')->get(['id', 'name', 'email']),
            'actions' => AuditLog::query()->select('action')->distinct()->orderBy('action')->pluck('action'),
            'filters' => [
                'q' => $q,
                'action' => $action,
                'user_id' => $userId,
                'date_from' => $dateFrom,
                'date_to' => $dateTo,
            ],
        ]);
    }
}

