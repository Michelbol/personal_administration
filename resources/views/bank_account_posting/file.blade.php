@extends('layouts.app')

@section('content')
        <div class="container">
            <form action="{{ route('bank_account_posting.read_file') }}" method="POST" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="row form-group">
                    <input type="file" multiple name="arquivo[]">
                </div>
                <div class="row">
                    <div class="float-left">
                        <button type="submit" class="btn btn-info">Ler arquivo</button>
                    </div>
                </div>
            </form>
        </div>
@endsection
