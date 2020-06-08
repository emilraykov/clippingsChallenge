<?php


namespace Exception;

use Exception;

class ExceptionHandler
{
    /**
     * @return void
     */
    public function setExceptionHandler()
    {
        set_exception_handler(array($this, 'handleException'));
    }

    /**
     * @param Exception $exception
     *
     * @return void
     */
    public function handleException(Exception $exception)
    {
        if ($exception instanceof BaseException) {
            echo $exception->getMessage() . PHP_EOL;
            die();
        }
    }
}