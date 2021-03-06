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
    <input type="hidden" id="id" name="id" value="{{ isset($carSupply) ? $carSupply->id : ''}}">
    <input type="hidden" id="car_id" name="car_id" value="{{ $car_id }}">
    <div class="row">
        <div class="col-4">
            <label for="kilometer">Km do Abastencimento</label>
            <input type="text" class="form-control money" id="kilometer" name="kilometer" value="{{ old('kilometer') ? old('kilometer') : (isset($carSupply) ? $carSupply->kilometer : '') }}">
        </div>
        <div class="col-3">
            <label for="liters">Litros Abastecidos</label>
            <input type="text" name="liters" id="liters" class="form-control money" maxlength="100"
                   value="{{ old('liters') ? old('liters') : (isset($carSupply) ?  $carSupply->liters : '')}}">
        </div>
        <div class="col-3">
            <label for="fuel">Combustíveis</label>
            <select name="fuel" id="fuel" class="form-control">
                @foreach(\App\Models\Enum\FuelEnum::getConstants() as $fuel => $key)
                    <option value="{{ $key }}">{{ $fuel }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="row">
        <div class="col-4">
            <label for="total_paid">Total Pago no Abastecimento</label>
            <input type="text" class="form-control money" name="total_paid" id="total_paid" maxlength="10"
                   value="{{ old('total_paid') ? old('total_paid') : (isset($carSupply) ?  $carSupply->total_paid : '')}}">
        </div>
        <div class="col-3">
            <label for="date">Data do Abastecimento</label>
            <input type="text" class="form-control date" required name="date" id="date" maxlength="10"
                   value="{{ old('date') ? old('date') : (isset($carSupply) ?  $carSupply->date : '')}}">
        </div>
        <div class="col-3">
            <label for="gas_station">Posto de Gasolina</label>
            <input type="text" class="form-control" required name="gas_station" id="gas_station" maxlength="150"
                   value="{{ old('gas_station') ? old('gas_station') : (isset($carSupply) ?  $carSupply->gas_station : '')}}">
        </div>
    </div>
</div>

@push('scripts')
    <script src="{{ asset('js/car/supply/form.js') }}"></script>
@endpush
