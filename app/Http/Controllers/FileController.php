<?php

namespace App\Http\Controllers;

use App\File;
use App\Http\Requests\ImageUploadFormRequest;
use App\Repositories\CsvData\CsvDataInterface;
use App\Repositories\ImageData\ImageDataInterface;
use DB;
use Illuminate\Http\Request;
use Throwable;

class FileController extends Controller
{
    protected
        $img,   // Image/File repo
        $csv,   // CSV data repo
        $imageUploadPath;   // Image upload destination path



    public function __construct(ImageDataInterface $img, CsvDataInterface $csv)
    {
        // requiring authentication
        $this->middleware('auth');

        // getting Model through service container
        $this->img = $img;
        $this->csv = $csv;

        // image upload path
        $this->imageUploadPath = config('app.fileUpload.path') . DIRECTORY_SEPARATOR;
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

        // file upload constraints
        $maxFileLimit = config('app.fileUpload.limit');
        $allowedFileExtensions = json_encode(config('app.fileUpload.extensions'));

        $data = [
            'csvBatches' => $csvBatches,
            'files' => $files,
            'maxFileLimit' => $maxFileLimit,
            'allowedExtensions' => $allowedFileExtensions
        ];

        return view('img.upload_file', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param ImageUploadFormRequest $request
     * @return int
     */
    public function store(ImageUploadFormRequest $request)
    {
        // get data from request
        $allFiles = collect($request->allFiles()['files']);
        $batch = $request->get('batch');

        // preparing data
        $data = [];
        foreach($allFiles as $file) {

            // file name with extension
            $fileName = $file->getClientOriginalName();

            // preparing data to store file metadata in DB
            array_push($data, [
                'import_batch' => $batch,
                'name' => $fileName,
                'path' => $this->imageUploadPath . $fileName
            ]);
        };


        // saving files meta to db
        $uploadedFiles = $this->img->createMany($data);

        // if all files not inserted
        if ($uploadedFiles->count() != count($data)) {
            return 0;
        }

        // copying files to storage
        foreach($allFiles as $file) {
            $file->move($this->imageUploadPath, $file->getClientOriginalName());
        }

        return $uploadedFiles->pluck('name');
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
