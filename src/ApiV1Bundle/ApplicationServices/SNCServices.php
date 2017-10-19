<?php
namespace ApiV1Bundle\ApplicationServices;

use Symfony\Component\DependencyInjection\Container;

/**
 * Class SNTServices
 * @package ApiV1Bundle\ApplicationServices
 */
class SNCServices
{
    private $container;

    /**
     * SNTServices constructor.
     *
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }
}
