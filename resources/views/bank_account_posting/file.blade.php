@extends('layouts.app')

@section('content')
        <div class="container">
            <form action="{{ routeTenant('bank_account_posting.read_file') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <h3>Arquivos .txt</h3>
                <div class="row form-group">
                    <input type="file" multiple name="arquivostxt[]" accept=".txt">
                </div>
                <h3>Arquivos .ofx</h3>
                <div class="row form-group">
                    <input type="file" multiple name="arquivosofx[]" accept=".ofx">
                </div>
                <div class="row">
                    <div class="float-left">
                        <button type="submit" class="btn btn-info">Ler arquivos</button>
                    </div>
                </div>
            </form>
        </div>
@endsection