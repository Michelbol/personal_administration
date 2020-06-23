<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 01/04/2019
 * Time: 20:20
 */

use App\Tenant\TenantManager;
use Carbon\Carbon;

if(!function_exists('routeTenant')){
    /**
     * Generate the URL to a named route.
     *
     * @param array|string $name
     * @param array $params
     * @param bool $absolute
     * @return string
     */
    function routeTenant($name, $params = [], $absolute = true){
        $tenantManager = app(TenantManager::class);
        return route($name, $params+[ config('tenant.route_param') => $tenantManager->routeParam() ], $absolute);
    }
}
if(!function_exists('getClass')){
    /**
     * Generate the URL to a named route.
     *
     * @param $object
     * @return string
     */
    function getClass($object){
        $explode = explode('\\',get_class($object));
        return $explode[count($explode)-1];
    }
}


if(!function_exists('getBtnAction')) {
    function getBtnAction($botoes = [])
    {
        $return = '<div class="btn-group">';

        for ($i = 0; $i < count($botoes); $i++) {
            $botoes[$i]['type'] = $botoes[$i]['type'] ?? "";
            $botoes[$i]['id'] = $botoes[$i]['id'] ?? "";
            $botoes[$i]['name'] = isset($botoes[$i]['name']) ? $botoes[$i]['name'] : "Lançamentos";
            $botoes[$i]['url'] = $botoes[$i]['url'] ?? "";
            $botoes[$i]['tooltip'] = $botoes[$i]['tooltip'] ?? "";
            $botoes[$i]['disabled'] = isset($botoes[$i]['disabled']) ? ($botoes[$i]['disabled'] === false ? 'disabled' : '') : '';
            $botoes[$i]['class'] = $botoes[$i]['class'] ?? "";
            if ($botoes[$i]['type'] == 'delete') {
                $botoes[$i]['class'] = $botoes[$i]['class'] === '' ? 'btn btn-dark btn-sm' : $botoes[$i]['class'];
//                dd($botoes[$i]['name']);
                $botoes[$i]['name'] = $botoes[$i]['name'] === "Lançamentos" ? "Excluir" : $botoes[$i]['name'];
                $return .= "<a 	class='" . $botoes[$i]["class"] . "'
                                data-toggle='tooltip'
                                title='" . $botoes[$i]['tooltip'] . "'
                                  href='#'
                                  onclick=" . '"' . "event.preventDefault();
                                                        if(confirm('Deseja excluir este item?')){
                            document.getElementById('form-delete-" . $botoes[$i]['id'] . "').submit();}" . '"' . ">
                            " . $botoes[$i]['name'] . "
                            </a>
                            <form   action='" . $botoes[$i]['url'] . "' method='post'
                                    id='form-delete-" . $botoes[$i]["id"] . "'>
                                " . csrf_field() . "
                                <input type='text' hidden name='_method' value='DELETE'>
                            </form>";
            } else if ($botoes[$i]['type'] == 'edit') {
                $return .= '<a href="' . $botoes[$i]['url'] . '" data-id="' . $botoes[$i]['id'] . '" class="btn btn-primary btn-sm modal-edit" id="form-edit-' . $botoes[$i]["id"] . '">
                            Editar
                        </a>';
            } else if ($botoes[$i]['type'] == 'others') {
                $return .= '<button
                                type="button"
                                data-toggle="tooltip"
                                title="' . $botoes[$i]['tooltip'] . '"
                                class="btn btn-default ' . $botoes[$i]['name'] . '"
                                ' . $botoes[$i]['disabled'] . '>' .
                    '<i class="' . $botoes[$i]['class'] . '"></i>' .
                    '</button>';
            } else if ($botoes[$i]['type'] == 'print') {
                $return .= '<a
                                class="btn btn-default btn--icon ' . $botoes[$i]['nome'] . " " . ($botoes[$i]['disabled'] === false ? 'disabled' : '') . '"
                                role="button"
                                href="' . $botoes[$i]['url'] . '"
                                target="_blank"
                                style="font-size: 1.2em"
                                data-toggle="tooltip"
                                data-original-title="' . $botoes[$i]['tooltip'] . '">' .
                    '<i class="' . $botoes[$i]['class'] . '"></i>' .
                    '</a>';
            } else if ($botoes[$i]['type'] == 'other-a') {
                $return .= '<a href="' . $botoes[$i]['url'] . '" class="btn btn-danger btn-sm">';
                $return .= $botoes[$i]['name'];
                $return .= '</a>';
            }

        }
        $return .= '</div>';

        return $return;
    }
}

if(!function_exists('formatDataCarbon')) {
    /**
     * @param $value
     * @return Carbon|null
     */
    function formatDataCarbon($value)
    {
        $data = null;
        $hora = 0;
        $min = 0;

        if (isset($value) && (!$value instanceof Carbon)) {
            $formatodata = substr($value, 2, 1);
            if ($formatodata == '-' || $formatodata == '/') {
                $dia = substr($value, 0, 2);
                $mes = substr($value, 3, 2);
                $ano = substr($value, 6, 4);
            } else {
                $dia = substr($value, 8, 2);
                $mes = substr($value, 5, 2);
                $ano = substr($value, 0, 4);
            }
            $datahora = explode(' ', $value);
            if (isset($datahora[1])) {
                $hora = substr($datahora[1], 0, 2);
                $min = substr($datahora[1], 3, 2);
            }

            $data = Carbon::create($ano, $mes, $dia, $hora, $min);
        } else if ($value instanceof Carbon) {
            $data = $value;
        }

        return $data;
    }
}
if(!function_exists('formatReal')) {
    function formatReal($value)
    {
        $aux = 0;
        if ($value) {
            if (strpos($value, ',')) {
                $aux = str_replace(".", "", $value);
                $aux = str_replace(",", ".", $aux);
            } else {
                $aux = $value;
            }
            $value = str_replace("%", "", $aux);
//            $value = str_replace("-", "", $aux);
        }

        return number_format($value, 2, '.', '');
    }
}
if(!function_exists('getFormatReal')) {
    function getFormatReal($value)
    {
        return number_format($value, 2, ',', '.');
    }
}

if(!function_exists('formatGetData')){
    function formatGetData($value)
    {
        $data = null;
        if (isset($value)) {
            $data = strtotime($value);
            $data = date('d/m/Y', $data);
        }
        return $data;
    }
}

if(!function_exists('cleanNumber')){
    function cleanNumber($value)
    {
        if (isset($value)) {
            $value = preg_replace("/[^0-9]/", '', $value);
        }
        return $value;
    }
}
