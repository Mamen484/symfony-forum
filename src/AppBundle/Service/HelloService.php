<?php
/**
 * Created by PhpStorm.
 * User: DJIBRIL
 * Date: 08/09/2017
 * Time: 09:59
 */

namespace AppBundle\Service;


class HelloService
{
    /**
     * @var string
     */
   private $name;

    /**
     * @var HelloRenderer
     */
   private $renderer;

    /**
     * HelloService constructor.
     * @param string $name
     * @param helloRenderer $renderer
     */
    public function __construct($name, HelloRenderer $renderer)
    {
        $this->name = $name;
        $this->renderer = $renderer;
    }

    public function sayHello()
    {

        return $this->renderer->render("Hello $this->name");
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return HelloService
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

}