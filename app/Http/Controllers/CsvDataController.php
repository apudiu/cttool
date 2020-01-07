<?php

namespace App\Http\Controllers;

use App\CsvData;
use App\Exceptions\CSVException;
use App\Http\Requests\CSVFileFormRequest;
use App\Report;
use App\Traits\Report as ReportTrait;
use App\Repositories\CsvData\CsvDataInterface;
use EasyCSV\Reader;
use Illuminate\Http\Request;

class CsvDataController extends Controller
{
    use ReportTrait;

    protected $csvData;

    public function __construct(CsvDataInterface $csv)
    {
        // requiring authentication
        $this->middleware('auth');

        // getting Model through service container
        $this->csvData = $csv;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // existing csv data
        $csvData = $this->csvData->getAll(3, [], [], ['desc', 'created_at']);

        $data = [
            'csvData' => $csvData,
        ];

        return view('csv.import_data', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CSVFileFormRequest $request)
    {
        // prevent uploading csv if there's pending upload batch
        if ($this->checkForPendingUploadBatch()) {
            // redirecting back
            return redirect()->back()->with('status', 'Upload pending batches to upload again.');
        }

        // getting CSV file from request
        $csvFile = $request->file('csv_file');

        // getting values from CSV
        try {

            $rows = $this->getCSVValues($csvFile);

        } catch (CSVException $e) {
            return redirect()->back()->with('status', $e->getMessage());
        }

        // inserting into DB
        // Filtering out previously uploaded files
        $data = $this->checkForExisting($rows['data']);

        if ($data['itemsCount'] > 0) {

            $this->csvData->createMany($data['items']);

            // if items removed
            if ($data['removedCount'] > 0) {

                $status = [
                    'batch' => $rows['batch'],
                    'count' => "Excluded: {$data['removedCount']}/{$data['totalCount']} files because of duplication.",
                    'files' => $data['itemsRemoved']
                ];

                // saving log report to reports
                $this->logReportExistingData($data['itemsRemoved']);

            } else {
                $status = 'All data has been imported.';
            }

            // redirecting back
            return redirect()->back()->with('status', $status);
        } else {

            // redirecting back
            return redirect()->back()->with('status', 'Duplicate list ! No data imported for upload.');
        }
    }


    /**
     * Deletes all CSV data
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy() {

        // deleting all csv data (know that its not that efficient)
        getAuthUser()->csv_data()->delete();

        // redirect
        return redirect()->back()->with('status', 'CSV Cleared.');
    }


    /**
     * Gets all CSV rows
     *
     * @param $csvFile
     * @return array
     * @throws CSVException
     */
    protected function getCSVValues($csvFile) {

        // get all CSV content
        $csv = new Reader($csvFile);
        $data = $csv->getAll();

        // return if csv contains more than allowed max
        if (count($data) > config('app.fileUpload.limit')) {
            $max = config('app.fileUpload.limit');
            throw new CSVException('Max limit exceeded. Maximum of ' . $max . ' rows allowed', 413);
        }

        $userId = getAuthUser()->id;
        $batch  = time();

        // processing each row to be inserted into db
        $data = array_map(function($item) use ($userId, $batch) {

            // non csv fields
            $excludeFields = ['user_id','import_batch','file_name'];

            // excluding non csv fields from db fields
            $keys = array_diff($this->csvData->getAttributes(), $excludeFields) ;

            // csv data
            $values = array_values($item);

            // creating new array with db fields and csv data
            $item = array_combine($keys, $values);

            // add user_id, import_batch and file_name in the row
            $userId = ['user_id' => $userId];
            $batch = ['import_batch' => $batch];
            $fileName = ['file_name' => mb_basename($item['document_path'])];

            $item = array_merge($userId, $batch, $item, $fileName);

            return $item;
        }, $data);

        return [
            'batch' => $batch,
            'data' => $data
        ];
    }

    /**
     * Filters out previously uploaded files
     * @param array $csvData
     * @return array
     */
    public function checkForExisting(array $csvData) :array {

        $removedItems = [];

        $items = array_filter($csvData, function ($item) use (&$removedItems) {

            $fileName = $item['file_name'];

            // is the file found in report
            $exists = (Report::where('name', $fileName)->count() < 1);

            // keeping removed items
            if (!$exists) {
                array_push($removedItems, $item);
            }

            return $exists;
        });

        return [
            // number of elements before processing
            'totalCount' => count($csvData),
            // number of elements which has been removed
            'removedCount' => count($csvData) - count($items),
            // number of elements remaining after removal
            'itemsCount' => count($items),

            // elements after removal
            'items' => $items,
            // removed elements
            'itemsRemoved' => $removedItems
        ];
    }

    /**
     * Checks if there's pending batch for upload
     * @return bool
     */
    public function checkForPendingUploadBatch() :bool {
        return $this->csvData->model()->count() ? true : false;
    } 
}
