@extends('tribal.layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <h4>Tipos de Coleta</h4>
        </div>
        <div class="row">
            <div class="form-check form-check-inline col-1">
                <input class="form-check-input" type="checkbox" checked value="15" id="little">
                <label class="form-check-label" for="little">
                    Pequena
                </label>
            </div>
            <div class="form-check form-check-inline col-1">
                <input class="form-check-input" type="checkbox" checked value="6" id="medium">
                <label class="form-check-label" for="medium">
                    Média
                </label>
            </div>
            <div class="form-check form-check-inline col-1">
                <input class="form-check-input" type="checkbox" checked value="3" id="big">
                <label class="form-check-label" for="big">
                    Grande
                </label>
            </div>
            <div class="form-check form-check-inline col-1">
                <input class="form-check-input" type="checkbox" checked value="2" id="extreme">
                <label class="form-check-label" for="extreme">
                    Extrema
                </label>
            </div>
        </div>
        <div class="row">
            <div class="col-2">
                <label for="lancer">
                    <img src="{{ asset('images/tribal/unit_spear.png') }}" alt="">
                    Lanceiro
                </label>
                <input type="number" class="form-control" name="lancer" id="lancer">
            </div>
            <div class="col-2">
                <label for="swordsman">
                    <img src="{{ asset('images/tribal/unit_sword.png') }}" alt="">
                     Espadachin
                </label>
                <input type="number" class="form-control" name="swordsman" id="swordsman">
            </div>
            <div class="col-2">
                <label for="barbarian">
                    <img src="{{ asset('images/tribal/unit_axe.png') }}" alt="">
                     Bárbaro
                </label>
                <input type="number" class="form-control" name="barbarian" id="barbarian">
            </div>
            <div class="col-2">
                <label for="archer">
                    <img src="{{ asset('images/tribal/unit_archer.png') }}" alt="">
                     Arqueiro
                </label>
                <input type="number" class="form-control" name="archer" id="archer">
            </div>
            <div class="col-2">
                <label for="light-cavalry">
                    <img src="{{ asset('images/tribal/unit_light.png') }}" alt="">
                     CL
                </label>
                <input type="number" class="form-control" name="light-cavalry" id="light-cavalry">
            </div>
            <div class="col-1">
                <label for="archer-horseback">
                    <img src="{{ asset('images/tribal/unit_marcher.png') }}" alt="">
                     AC
                </label>
                <input type="number" class="form-control" name="archer-horseback" id="archer-horseback">
            </div>
            <div class="col-1">
                <label for="heavy-cavalry">
                    <img src="{{ asset('images/tribal/unit_heavy.png') }}" alt="">
                     CP
                </label>
                <input type="number" class="form-control" name="heavy-cavalry" id="heavy-cavalry">
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-12">
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th class="collect-little" scope="col">Pequena</th>
                        <th class="collect-medium" scope="col">Média</th>
                        <th class="collect-big" scope="col">Grande</th>
                        <th class="collect-extreme" scope="col">Extrema</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr id="lancer-row">
                        <th class="fix-th" scope="row"><img src="{{ asset('images/tribal/unit_spear.png') }}" alt=""> Lanceiro</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    <tr id="swordsman-row">
                        <th class="fix-th" scope="row"><img src="{{ asset('images/tribal/unit_sword.png') }}" alt=""> Espadachin</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    <tr id="barbarian-row">
                        <th class="fix-th" scope="row"><img src="{{ asset('images/tribal/unit_axe.png') }}" alt=""> Bárbaro</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    <tr id="archer-row">
                        <th class="fix-th" scope="row"><img src="{{ asset('images/tribal/unit_archer.png') }}" alt=""> Arqueiro</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    <tr id="light-cavalry-row">
                        <th class="fix-th" scope="row"><img src="{{ asset('images/tribal/unit_light.png') }}" alt=""> Cavalaria Leve</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    <tr id="archer-horseback-row">
                        <th class="fix-th" scope="row"><img src="{{ asset('images/tribal/unit_marcher.png') }}" alt=""> Arqueiro a Cavalo</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    <tr id="heavy-cavalry-row">
                        <th class="fix-th" scope="row"><img src="{{ asset('images/tribal/unit_heavy.png') }}" alt=""> Cavalaria Pesada</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    <tr id="resources-row">
                        <th class="fix-th" scope="row"><span class="icon header res"></span> Recursos</th>
                        <td class="collect-little">0</td>
                        <td class="collect-medium">0</td>
                        <td class="collect-big">0</td>
                        <td class="collect-extreme">0</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script src="{{ asset('js/tribal/collect.js') }}"></script>
@endpush
