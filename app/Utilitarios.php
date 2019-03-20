<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 20/03/2019
 * Time: 19:25
 */

namespace App;


class Utilitarios
{
    public static function getBtnAction($botoes = []){
        $return = '<div class="btn-group">';

        for ($i = 0; $i < count($botoes); $i++ ){
            if ($botoes[$i]['type'] == 'delete'){
                $return .= '<a 	class="btn btn-dark btn-sm"
	                            href="'.$botoes[$i]['url'].'"
	                            onclick="event.preventDefault();
	                            if(confirm("Deseja excluir este item?")){
	                            document.getElementById("form-delete-'.$botoes[$i]['id'].').submit();}">
                                Excluir
                            </a>
                            <form   action="'.$botoes[$i]['url'].'" method="post"
                                    id="form-delete-'.$botoes[$i]['id'].'">
                                '.csrf_field().'
                                <input type="text" hidden name="_method" value="DELETE">
                            </form>';
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
}