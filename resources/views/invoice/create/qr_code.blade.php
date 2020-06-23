@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>Incluir Nota Por Qr Code</h3>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ routeTenant('invoice.store.qr_code') }}" method="POST">
            {{ csrf_field() }}
            <div class="form-group">
                <div class="row">
                    <div class="col-4">
                        <label for="url_qr_code">Url QrCode</label>
                        <input
                            type="text"
                            name="url_qr_code"
                            id="url_qr_code"
                            class="form-control"
                            required
                            placeholder="Insira a url do qr code"
                            value="{{ old('url_qr_code') ?? ''}}">
                    </div>
                </div>
            </div>
            <div class="float-right">
                <button class="btn btn-primary" type="submit">Salvar</button>
                <a href="{{ routeTenant('invoice.index') }}" class="btn btn-info">Cancelar</a>
            </div>
        </form>
    </div>
@endsection
