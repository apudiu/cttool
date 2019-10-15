@extends('layouts.app')

@section('title', 'CSV Import')

@section('content')
<div class="container">
    <!--New data import-->
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-inline">CSV Import</div>
                    <div class="float-right">
                        <div class="d-inline hidden mr-1 loading">
                            <img class="" src="{{ asset('imgs/spinner.gif') }}" alt="Spinner img">
                        </div>
                    </div>
                </div>
                <div class="card-body">

                    <div class="alert alert-primary hidden" role="alert" id="csv-data-upload-alert">
                        Processing, please wait ...
                    </div>

                    @if (session('status'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('status') }}
                            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                    @endif

                        <form action="{{ route('csv.store') }}"
                              method="POST"
                              enctype="multipart/form-data"
                              id="csv-upload-form">
                            @csrf

                            <div class="input-group">
                                <div class="custom-file">
                                    <input type="file" name="csv_file" accept=".csv">
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-secondary csv-upload-btn" type="submit">Upload</button>
                                </div>
                            </div>
                            @if($errors->has('csv_file'))
                                <div class="text-danger">
                                    <span>{{ $errors->first('csv_file') }}</span>
                                </div>
                            @endif
                        </form>
                </div>
            </div>
        </div>
    </div>
    
    <!--Existing data display-->
    <div class="row justify-content-center">
        <div class="col">
            <div class="card mt-5">
                <div class="card-header">
                    <div class="d-inline">CSV Data</div>
                    <div class="float-right">
                        <a class="btn btn-sm btn-primary"
                           href="#"
                           onclick="event.preventDefault();document.getElementById('csv-delete-form').submit();">Clear Data</a>
                        <form action="{{ route('csv.delete') }}"
                              method="post"
                              id="csv-delete-form"
                              hidden>
                            @csrf
                            @method('delete')
                        </form>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-scroll">
                            <thead>
                                <tr>
                                    @forelse(($c=$csvData->first()) ? $c->getAttributes() : [] as $attribute => $value)
                                        <th>{{ dashToSpace($attribute, '_') }}</th>
                                        @empty
                                    @endforelse
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($csvData as $data)
                                    <tr>
                                        @forelse($data->getAttributes() as $field)
                                            @if($loop->index == 0)
                                                <td>{{ $loop->parent->index + 1 }}</td>
                                            @else
                                                <td>{{ $field }}</td>
                                            @endif
                                        @empty
                                            <td>Empty!</td>
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

            $('#csv-upload-form').submit(function(e) {
                // preventing form upload
                e.preventDefault();

                // showing status
                $('#csv-data-upload-alert').show();

                // submitting form after * seconds
                setTimeout(function () {
                    $('#csv-upload-form')[0].submit();
                }, 1500);
            });
        });
    </script>
@endsection
