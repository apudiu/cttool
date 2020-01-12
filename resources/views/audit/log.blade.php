@extends('layouts.app')

@section('title', 'Audit Log')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-6">
                            <div class="d-inline ml-0 mr-auto">Audit Log</div>
                        </div>
                        <div class="col-6"></div>
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
                                <th>Time</th>
                                <th>User</th>
                                <th>IP</th>
                                <th>Action</th>
                                <th>Details</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>{{ $loop->index +1 }}</td>
                                    <td>{{ formatDateTime($log->created_at) }}</td>
                                    <td>{{ $log->user }}</td>
                                    <td>{{ $log->ip }}</td>
                                    <td>{{ $log->action }}</td>
                                    <td>{{ $log->details }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">No data!</td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer">
                    {{--Links only if pagination exists--}}
                    @if(method_exists($logs, 'render'))
                        {!! $logs->render() !!}
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
