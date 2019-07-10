@extends('layouts.app')

@section('title', 'CSV Import')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">CSV Import</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col">
            <div class="card mt-5">
                <div class="card-header">CSV Data</div>

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
    </script>
@endsection
