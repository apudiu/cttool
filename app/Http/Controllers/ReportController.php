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

        // paginate according to user date range if available
        if (request()->has('date-range')) {
            // preparing date range
            $dateRange = $this->prepareDateRange($rawDateRange);

            $report = Report::orderByDesc('time')->whereBetween('time', $dateRange)->get();
        } else {
            $report = Report::orderByDesc('time')->paginate($perPageRecords);
        }

        $data = [
            'report' => $report,
            'rawDateRange' => str_replace(',', ' - ', $rawDateRange), // formatting raw
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
}
