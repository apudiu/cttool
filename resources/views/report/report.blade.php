@extends('layouts.app')

@section('title', 'Report')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-8">
                            <div class="d-inline ml-0 mr-auto">File Upload Report</div>
                        </div>
                        <div class="col-4">
                            <form action="{{ route('report.index') }}">
                                <div class="input-group">
                                    <input type="hidden" id="report-d-range" name="date-range">
                                    <input type="text"
                                           id="report-date-range"
                                           class="form-control"
                                           placeholder="{{ empty($rawDateRange) ? 'Date range' : $rawDateRange }}"
                                           autocomplete="off"
                                           required>
                                    <div class="input-group-append">
                                        <button class="btn btn-primary" type="submit">Search</button>
                                        <a href="{{ route('report.index') }}" class="btn btn-secondary">Clear</a>
                                    </div>
                                </div>
                            </form>
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
                        <table class="table table-sm table-hover">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>File</th>
                                <th>Status</th>
                                <th>Time</th>
                                <th>Reason</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($report as $file)
                                <tr>
                                    <td>{{ $file->id }}</td>
                                    <td>{{ $file->name }}</td>
                                    <td>
                                        @if($file->status == 'SUCCESS')
                                            <span class="badge badge-secondary">{{ $file->status }}</span>
                                        @else
                                            <span class="badge badge-warning">{{ $file->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ formatDateTime($file->time) }}</td>
                                    <td>{{ $file->reason }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">No data!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {{--Links only if pagination exists--}}
                    @if(method_exists($report, 'render'))
                        {!! $report->render() !!}
                    @endif
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
