<?php

namespace App\Http\Controllers;

use App\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $perPageRecords = config('app.report.per-page');
        $rawDateRange = request()->get('date-range');
        $batch = request()->get('batch');

        // Applying user filters to report
        // preparing date range
        $dateRange = $this->prepareDateRange($rawDateRange);

        if ($this->requestHas('date-range') && $this->requestHas('batch')) { // both date range & batch

            $report = Report::orderByDesc('time')
                ->where('import_batch',$batch)
                ->whereBetween('time', $dateRange)
                ->get();

        } elseif ($this->requestHas('date-range')) { // date range only

            $report = Report::orderByDesc('time')
                ->whereBetween('time', $dateRange)
                ->get();

        } elseif ($this->requestHas('batch')) { // batch only

            $report = Report::orderByDesc('time')
                ->where('import_batch', $batch)
                ->get();

        } else { // all records
            $report = Report::orderByDesc('time')
                ->paginate($perPageRecords);
        }


        // log audit log
        setAuditLog([
            'user' => request()->user()->name,
            'ip' => request()->ip(),
            'action' => config('app.audit.log-types.access-report'),
            'details' => "Accessed file upload report"
        ]);

        $data = [
            'batch' => $batch,
            'report' => $report,
            'dateRange' => str_replace(',', ' - ', $rawDateRange),
            'rawDateRange' => $rawDateRange, // formatting raw
        ];

        return view('report.report', $data);
    }

    /**
     * Prepares given string date range to usable array
     * @param string $dateRangeString
     * @return array
     */
    private function prepareDateRange($dateRangeString) {
        
        // suffixes to add with dates 
        $suffixes = [' 00:00:00', ' 23:59:59'];
        
        // date range array
        $dateRange = explode(',', $dateRangeString);
        
        // add suffixes accordingly
        for ($i = 0; $i < count($dateRange); $i++) {
            $dateRange[$i] = $dateRange[$i] . $suffixes[$i];
        }

        return $dateRange;
    }

    /**
     * Checks if a given key exists in request and not empty
     * @param string $paramName
     * @return bool
     */
    private function requestHas(string $paramName) :bool {

        $requestValue = request()->get($paramName);

        if (request()->has($paramName)) {

            if (!empty($requestValue)) {

                return true;
            } else {
                return false;
            }

        } else {
            return false;
        }
    }
}
