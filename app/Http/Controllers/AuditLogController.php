<?php

namespace App\Http\Controllers;

use App\Audit_log;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @param Audit_log $audit_log
     * @return \Illuminate\Http\Response
     */
    public function index(Audit_log $audit_log)
    {
        $perPageRecords = config('app.audit.per-page');

        $logs = $audit_log::orderByDesc('created_at')->paginate($perPageRecords);

        // log audit log
        setAuditLog([
            'user' => request()->user()->name,
            'ip' => request()->ip(),
            'action' => config('app.audit.log-types.access-audit'),
            'details' => "Accessed Audit Log"
        ]);

        $data = [
            'logs' => $logs,
        ];

        return view('audit.log', $data);
    }
}
