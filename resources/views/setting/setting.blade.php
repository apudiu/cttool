@extends('layouts.app')

@section('title', 'System settings')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card mt-3">
                <div class="card-header">Configurations</div>

                <div class="card-body bg-dark text-white">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                        <form action="{{ route('setting.store') }}" method="post" autocomplete="off" id="setting-form">
                            <div class="form-group">
                                <label for="phpname">PHP Name</label>
                                <input type="text"
                                       class="form-control text-bold"
                                       id="phpname"
                                       required
                                       name="php_name"
                                       value="{{ $setting->php_name }}">

                                <small class="form-text text-muted">PHP name in shell. like php56 ...</small>
                            </div>
                            <div class="form-group">
                                <label for="docudexpath">Docudex Path</label>
                                <input type="text"
                                       class="form-control text-bold"
                                       id="docudexpath"
                                       required
                                       name="docudex_path"
                                       value="{{ $setting->docudex_path }}">

                                <small class="form-text text-muted">Docudex full path</small>
                            </div>
                            <div class="form-group">
                                <label for="filespath">Files Path</label>
                                <input type="text"
                                       class="form-control text-bold"
                                       id="filespath"
                                       required
                                       name="files_path"
                                       value="{{ $setting->files_path }}">

                                <small class="form-text text-muted">Files (that will be uploaded) full path</small>
                            </div>
                            <div class="form-group">
                                <label for="confpath">Config Path</label>
                                <input type="text"
                                       class="form-control text-bold"
                                       id="confpath"
                                       required
                                       name="config_path"
                                       value="{{ $setting->config_path }}">

                                <small class="form-text text-muted">Configuration files full path</small>
                            </div>

                            <div class="mt-2">
                                <button type="submit" class="btn btn-primary float-right">Save</button>
                            </div>
                        </form>
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
