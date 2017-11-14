<?php

namespace ApiV1Bundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class ControllerTestCase extends WebTestCase
{
    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        self::bootKernel();
        $kernel = static::$kernel;
        // application
        $application = new Application($kernel);
        $application->setAutoExit(false);
        // call command
        $input = new ArrayInput([
            'command' => 'doctrine:schema:create'
        ]);
        $output = new BufferedOutput();
        $application->run($input, $output);
    }
    
    /**
     * {@inheritDoc}
     */
    public static function tearDownAfterClass()
    {
        // kernel
        self::bootKernel();
        $kernel = static::$kernel;
    }
}
