<?php
namespace ApiV1Bundle\ApplicationServices;
use ApiV1Bundle\Entity\Factory\AgenteFactory;
use ApiV1Bundle\Entity\Validator\AgenteValidator;
use ApiV1Bundle\Entity\Validator\UserValidator;
use ApiV1Bundle\Repository\AgenteRepository;
use Symfony\Component\DependencyInjection\Container;

/**
 * Class AgenteServices
 * @package ApiV1Bundle\ApplicationServices
 */
class AgenteServices extends SNCServices
{
    private $agenteRepository;
    private $agenteValidator;
    private $userValidator;

    public function __construct(
        Container $container,
        AgenteRepository $agenteRepository,
        AgenteValidator $agenteValidator,
        UserValidator $userValidator
    )
    {
        parent::__construct($container);
        $this->agenteRepository = $agenteRepository;
        $this->agenteValidator = $agenteValidator;
        $this->userValidator = $userValidator;
    }

    /**
     * @param array $params Array con los datos a crear
     * @param $sucess | funcion que devuelve si tuvo éxito
     * @param $error | funcion que devuelve si ocurrio un error
     * @return mixed
     */
    public function create($params, $sucess, $error)
    {
        $agenteFactory = new AgenteFactory($this->agenteValidator, $this->userValidator);

        $validateResult = $agenteFactory->create($params);

        return $this->processResult(
            $validateResult,
            function ($entity) use ($sucess) {
                return call_user_func($sucess, $this->agenteRepository->save($entity));
            },
            $error
        );
    }
}