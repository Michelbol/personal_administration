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
            <label for="brand">Marca</label>
            <select name="brand" id="brand" class="form-control">
                <option value="">Selecione uma Marca</option>
                @foreach($brands as $brand)
                    <option
                        value="{{ $brand->codigo }}"
                        {{ isset($car) ? ($brand->codigo === (int) $car->brand ? 'selected' : '') : '' }}
                    >{{ $brand->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-4">
            <label for="model">Modelo</label>
            <select name="model" id="model" class="form-control">
                <option value="">Selecione um modelo</option>
            </select>
        </div>
        <div class="col-4">
            <label for="year">Ano</label>
            <select name="year" id="year" class="form-control">
                <option value="">Selecione um Ano</option>
            </select>
        </div>
        <div class="col-4">
            <label for="license_plate">Placa do Carro</label>
            <input type="text" name="license_plate" id="license_plate" class="form-control" maxlength="100" required
                   value="{{ old('license_plate') ? old('license_plate') : (isset($car) ?  $car->license_plate : '')}}">
        </div>
        <div class="col-4">
            <label for="annual_licensing">Vencimento Licença Anual</label>
            <input type="text" class="form-control date" required name="annual_licensing" id="annual_licensing" maxlength="10"
                   value="{{ old('annual_licensing') ? old('annual_licensing') : (isset($car) ?  $car->annual_licensing : '')}}">
        </div>
        <div class="col-4">
            <label for="annual_insurance">Vencimento Seguro Obrigatório</label>
            <input type="text" class="form-control date" required name="annual_insurance" id="annual_insurance" maxlength="10"
                   value="{{ old('annual_insurance') ? old('annual_insurance') : (isset($car) ?  $car->annual_insurance : '')}}">
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/car/scripts.js') }}"></script>
    <script>
        @if(isset($car))
        selectedModel = "{{ $car->model }}";
        selectedYear = "{{ $car->year }}";
        @endif
    </script>
@endpush
