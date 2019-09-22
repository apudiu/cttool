<?php

namespace App\Http\Controllers;

use App\Repositories\CsvData\CsvDataInterface;
use App\Repositories\ImageData\ImageDataInterface;
use App\Setting;
use App\Traits\Bash;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    use Bash;

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
    {
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

    public function bulkUpload(Request $request) {

        // if dry run requested
        $dryRun = ($request->get('dry'));

        return $this->executeRunner($dryRun);
    }
}
