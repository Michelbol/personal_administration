@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<div class="form-group">
    <input type="hidden" id="id" name="id" value="{{ isset($car) ? $car->id : ''}}">
    <div class="row">
        <div class="col-4">
            <label for="model">Modelo Do Carro</label>
            <input type="text" class="form-control" id="model" name="model" value="{{ old('model') ? old('model') : (isset($car) ? $car->model : '') }}">
        </div>
        <div class="col-3">
            <label for="license_plate">Placa do Carro</label>
            <input type="text" name="license_plate" id="license_plate" class="form-control" maxlength="100" required
                   value="{{ old('license_plate') ? old('license_plate') : (isset($car) ?  $car->license_plate : '')}}">
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label for="annual_licensing">Vencimento Licença Anual</label>
            <input type="text" class="form-control date" required name="annual_licensing" id="annual_licensing" maxlength="10"
                   value="{{ old('annual_licensing') ? old('annual_licensing') : (isset($car) ?  $car->annual_licensing : '')}}">
        </div>
        <div class="col-3">
            <label for="annual_insurance">Vencimento Seguro Obrigatório</label>
            <input type="text" class="form-control date" required name="annual_insurance" id="annual_insurance" maxlength="10"
                   value="{{ old('annual_insurance') ? old('annual_insurance') : (isset($car) ?  $car->annual_insurance : '')}}">
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/car/scripts.js') }}"></script>
@endpush