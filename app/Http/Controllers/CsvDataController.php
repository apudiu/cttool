<?php

namespace App\Http\Controllers;

use App\CsvData;
use App\Exceptions\CSVException;
use App\Http\Requests\CSVFileFormRequest;
use App\Repositories\CsvData\CsvDataInterface;
use EasyCSV\Reader;
use Illuminate\Http\Request;

class CsvDataController extends Controller
{
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
        // getting CSV file from request
        $csvFile = $request->file('csv_file');

        // getting values from CSV
        try {

            $rows = $this->getCSVValues($csvFile);

        } catch (CSVException $e) {
            return redirect()->back()->with('status', $e->getMessage());
        }

        // inserting into DB
        $this->csvData->createMany($rows);

        // redirecting back
        return redirect()->back()->with('status', 'All data has been imported.');
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

        return $data;
    }
}
