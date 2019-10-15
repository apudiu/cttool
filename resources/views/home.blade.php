@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-inline">Batches</div>
                    <div class="float-right">
                        <div class="d-inline hidden mr-1 loading">
                            <img class="" src="{{ asset('imgs/spinner.gif') }}" alt="Spinner img">
                        </div>
                        <div class="btn-group">
                            <button type="button"
                                    class="btn btn-sm btn-primary dropdown-toggle"
                                    data-toggle="dropdown">
                                Process
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item process" href="#" data-dry="1">DryRun</a>
                                <a class="dropdown-item process" href="#" data-dry="0">Run</a>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>Batch</th>
                                <th>Records</th>
                                <th>Date</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($batches as $batch)
                                <tr>
                                    <td>{{ ($loop->index + 1) }}</td>
                                    <td>{{ $batch->import_batch }}</td>
                                    <td>{{ $batch->count }}</td>
                                    <td>{{ formatDateTimeFromStamp($batch->import_batch) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No data!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">
                    <div class="d-inline">Console</div>
                    <div class="float-right">
                        <button type="button" class="btn btn-sm btn-secondary" id="console-clear">Clear</button>
                    </div>
                </div>

                <div class="card-body bg-dark text-white" id="console">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div id="log"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('onpage-js')
    <script>
    </script>
@endsection
