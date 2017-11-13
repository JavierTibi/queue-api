<?php
/**
 * Created by PhpStorm.
 * User: Javier
 * Date: 12/11/2017
 * Time: 7:01 PM
 */

namespace ApiV1Bundle\Controller;


use ApiV1Bundle\Entity\Turno;
use Symfony\Component\HttpFoundation\Request;

class TurnoController extends ApiController
{
    private $turnoServices;

    /**
     * Guardar Turno
     *
     * @param Request $request Espera el resultado de una petición como parámetro
     * @return mixed
     * @Post("/turnos/")
     */
    public function postAction(Request $request)
    {
        $params = $request->request->all();
        $this->turnoServices = $this->getTurnoServices();

        return $this->turnoServices->create(
            $params,
            function ($usuario) {
                return $this->respuestaOk('Turno guardado con éxito', [
                    'id' => $usuario->getId()
                ]);
            },
            function ($err) {
                return $this->respuestaError($err);
            }
        );
    }

    public function cambiarEstado(Request $request)
    {
        $params = $request->request->all();
        $this->turnoServices = $this->getTurnoServices();

        if (isset($params['estado'])) {

            if($params['estado'] == Turno::ESTADO_RECEPCIONADO) {

            }

            if($params['estado'] == Turno::ESTADO_EN_TRANCURSO) {

            }

            if($params['estado'] == Turno::ESTADO_TERMINADO) {

            }
        }

        return $this->respuestaError('El parametro estado no es válido.');
    }
}