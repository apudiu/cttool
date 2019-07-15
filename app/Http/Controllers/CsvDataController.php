<?php

namespace App\Http\Controllers;

use App\CsvData;
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
        $csvData = $this->csvData->getAll(3);

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
    public function store(Request $request)
    {
        // getting CSV file from request
        $csvFile = $request->file('csv_file');

        // getting values from CSV
        $rows = $this->getCSVValues($csvFile);

        // inserting into DB
        $this->csvData->createMany($rows);

        // redirecting back
        return redirect()->back()->with('status', 'All data has been imported.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function show(CsvData $csvData)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function edit(CsvData $csvData)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CsvData $csvData)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\CsvData  $csvData
     * @return \Illuminate\Http\Response
     */
    public function destroy(CsvData $csvData)
    {
        //
    }



    /**
     * Gets all CSV rows
     *
     * @param $csvFile
     * @return array
     */
    protected function getCSVValues($csvFile) {

        // get all CSV content
        $csv = new Reader($csvFile);
        $data = $csv->getAll();

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
            $fileName = ['file_name' => basename($item['document_path'])];

            $item = array_merge($userId, $batch, $item, $fileName);

            return $item;
        }, $data);

        return $data;
    }
}
