<?php

namespace App\Http\Controllers;

use App\Repositories\CsvData\CsvDataInterface;
use App\Repositories\ImageData\ImageDataInterface;
use App\Setting;
use App\Traits\Bash;
use App\Traits\Report;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use Bash, Report;

    protected $file, $csv;

    /**
     * Create a new controller instance.
     *
     * @param ImageDataInterface $img
     * @param CsvDataInterface $csv
     */
    public function __construct(ImageDataInterface $img, CsvDataInterface $csv)
    {
        $this->middleware('auth');

        // initiating trait constructor (like) function
        $this->reportConstruct();

        // getting repo's
        $this->file = $img;
        $this->csv = $csv;
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {   $this->getResultFromFile();

        $batches = $this->csv->model()
            ->selectRaw('import_batch, COUNT(*) AS count')
            ->orderByDesc('import_batch')
            ->groupBy('import_batch')
            ->get();

        $data = [
            'batches' => $batches,
        ];

        return view('home', $data);
    }

    /**
     * Bulk upload files with an option to dry run
     * @param Request $request
     * @return string
     * @throws \App\Exceptions\FileNotFoundException
     */
    public function bulkUpload(Request $request) {

        // if dry run requested
        $dryRun = ($request->get('dry'));

        // execute command
        $log = $this->executeRunner($dryRun);

        // logging report
        if (!$dryRun) {
            $this->logReport();

            // Send count of processed files for the user
            $log = $log . "<br />" . "Success: {$this->getResultCount('success')}, Failure: {$this->getResultCount('failure')}";
        }

        return $log ?? 'No log available!';
    }
}
