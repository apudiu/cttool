@extends('layouts.app')

@section('title', 'File Upload')

@section('content')
<div class="container">
    <!--New data import-->
    <div class="row justify-content-center">
        <div class="col">
            @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('status') }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            @endif

            <file-upload
                    :errors="{{ $errors }}"
                    :csv_batches="{{ $csvBatches }}"
                    :max_files_limit="{{ $maxFileLimit }}"
                    :allowed_extensions="{{ $allowedExtensions }}"></file-upload>

        </div>
    </div>
    
    <!--Existing data display-->
    <div class="row justify-content-center">
        <div class="col">
            <div class="card mt-4">
                <div class="card-header">Files</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-scroll">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Batch</th>
                                    <th>Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($files as $file)
                                    <tr>
                                        @forelse($files as $file)
                                            <td>{{ $loop->index }}</td>
                                            <td>{{ $file->import_batch }}</td>
                                            <td>{{ $file->name }}</td>
                                        @empty
                                            <td colspan="3">Empty!</td>
                                        @endforelse
                                    </tr>
                                    @empty
                                    <tr>
                                        <td class="text-center">No data available! </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('onpage-js')
    <script>
        $(document).ready(function() {
        });
    </script>
@endsection
