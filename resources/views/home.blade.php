@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Batches</div>

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
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($batches as $batch)
                                <tr>
                                    <td>{{ ($loop->index + 1) }}</td>
                                    <td>{{ $batch->import_batch }}</td>
                                    <td>{{ $batch->count }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-secondary batch-button">Process</button>
                                    </td>
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
    </div>
</div>
@endsection

@section('onpage-js')
    <script>
    </script>
@endsection
