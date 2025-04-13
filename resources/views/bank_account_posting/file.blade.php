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
            @if(Session::has('typeBankAccountPostingNotSaved'))
                <div class="container">
                    <h5>Foram encontrados alguns erros</h5>
                    <table class="table table-striped table-bordered" width="100%">
                        <thead>
                        <tr>
                            <th>Errors</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(Session::get('typeBankAccountPostingNotSaved') as $typeBankAccountPostingNotSaved)
                            <tr>
                                <th>{!! $typeBankAccountPostingNotSaved !!}</th>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
@endsection
