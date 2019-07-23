<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\ImageUploadFormRequest;
use App\Repositories\CsvData\CsvDataInterface;
use App\Repositories\ImageData\ImageDataInterface;
use Illuminate\Http\Request;

class FileController extends Controller
{
    protected $img, $csv;

    public function __construct(ImageDataInterface $img, CsvDataInterface $csv)
    {
        // requiring authentication
        $this->middleware('auth');

        // getting Model through service container
        $this->img = $img;
        $this->csv = $csv;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Accessing model in this way may not be ideal (although accessing it via repo) ...
        $csvBatches = $this->csv->model()->distinct()->orderByDesc('import_batch')->pluck('import_batch');

        $files = $this->img->getAll(3, [], [], ['desc', 'created_at']);

        $data = [
            'csvBatches' => $csvBatches,
            'files' => $files
        ];

        return view('img.upload_file', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ImageUploadFormRequest $request)
    {
        return $request->get('fileIndexes');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function show(File $file)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function edit(File $file)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, File $file)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\File  $file
     * @return \Illuminate\Http\Response
     */
    public function destroy(File $file)
    {
        //
    }
}
