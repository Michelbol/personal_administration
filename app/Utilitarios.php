<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/03/2019
 * Time: 19:25
 */

namespace App;


use Carbon\Carbon;

class Utilitarios
{
    public static function getBtnAction($botoes = []){
        $return = '<div class="btn-group">';

        for ($i = 0; $i < count($botoes); $i++ ){
            if ($botoes[$i]['type'] == 'delete'){
                $return .= "<a 	class='btn btn-dark btn-sm'
                                  href='#'
                                  onclick=".'"'."event.preventDefault();
                                                        if(confirm('Deseja excluir este item?')){
                            document.getElementById('form-delete-".$botoes[$i]['id']."').submit();}".'"'.">
                            Excluir
                            </a>
                            <form   action='".$botoes[$i]['url']."' method='post'
                                    id='form-delete-".$botoes[$i]["id"]."'>
                                ".csrf_field()."
                                <input type='text' hidden name='_method' value='DELETE'>
                            </form>";
            }else if($botoes[$i]['type'] == 'edit'){
                $return .= '<a href="'.$botoes[$i]['url'].'" class="btn btn-primary btn-sm">
                            Editar
                        </a>';
            }else if($botoes[$i]['type'] == 'outros'){
                $return .= '<button
                                type="button"
                                class="btn btn-default btn--icon '.$botoes[$i]['nome'].'"
                                style="font-size: 1.2em"
                                data-toggle="tooltip"
                                data-original-title="'.$botoes[$i]['tooltip'].'"
                                '.($botoes[$i]['disabled'] === false ? 'disabled' : '').'>'.
                    '<i class="'.$botoes[$i]['class'].'"></i>'.
                    '</button>';
            }else if($botoes[$i]['type'] == 'print'){
                $return .= '<a
                                class="btn btn-default btn--icon '.$botoes[$i]['nome']." ".($botoes[$i]['disabled'] === false ? 'disabled' : '').'"
                                role="button"
                                href="'.$botoes[$i]['url'].'"
                                target="_blank"
                                style="font-size: 1.2em"
                                data-toggle="tooltip"
                                data-original-title="'.$botoes[$i]['tooltip'].'">'.
                    '<i class="'.$botoes[$i]['class'].'"></i>'.
                    '</a>';
            }
            else if($botoes[$i]['type'] == 'other-a'){
                $return .= '<a href="'.$botoes[$i]['url'].'" class="btn btn-danger btn-sm">
                            Lançamentos
                        </a>';
            }

        }
        $return .= '</div>';

        return $return;
    }

    static function formatDataCarbon($value)
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
            if(isset($datahora[1])){
                $hora = substr($datahora[1], 0,2);
                $min = substr($datahora[1], 3,2);
            }

            $data = Carbon::create($ano, $mes, $dia, $hora, $min);
        } else if ($value instanceof Carbon) {
            $data = $value;
        }

        return $data;
    }

    static function formatReal($value)
    {
        $aux = 0;
        if ($value ) {
            if (strpos($value, ',')){
                $aux = str_replace(".", "", $value);
                $aux = str_replace(",", ".", $aux);
            }else{
                $aux = $value;
            }
            $aux = str_replace("%", "", $aux);
            $value = str_replace("-", "", $aux);
        }

        return number_format($value,2,'.','');
    }

    static function getFormatReal($value){
        return number_format($value, 2, ',', '.');
    }

    static function formatGetData($value)
    {
        $data = null;
        if (isset($value)) {
            $data = strtotime($value);
            $data = date('d/m/Y', $data);
        }
        return $data;
    }
}