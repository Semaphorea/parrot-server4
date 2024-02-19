<?php

namespace App\Services;

use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;
use Psr\Log\HandlerInterface;
use Monolog\Logger;
use Psr\Log\LogLevel;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class logService
{

    //protected $path="%kernel.logs_dir%/%kernel.environment%.log"; 
     protected $path="var/log/dev.log";    
   // protected $path;
    protected $lineformat = "[%datetime%] %level_name%: %message% %context% %extra%\n";
    public $log;

    function __construct(LoggerInterface $l, 
    //KernelInterface $kernel
    )
    {

     //   $path = $kernel->getLogDir();

        $this->log = new Logger("Defined Log");
        $formater = new LineFormatter($this->lineformat);
        $handler = new StreamHandler($this->path, LogLevel::DEBUG);
        $l->warning($this->path);
        $handler->setFormatter($formater);
        $this->log->pushHandler($handler);
    }




    /**
     * System is unusable.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function emergency(string|\Stringable $message, array $context = []): void
    {
        $this->log->emergency($message, $context);
    }

    /**
     * Action must be taken immediately.
     *
     * Example: Entire website down, database unavailable, etc. This should
     * trigger the SMS alerts and wake you up.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function alert(string|\Stringable $message, array $context = []): void
    {
        $this->log->alert($message, $context);
    }

    /**
     * Critical conditions.
     *
     * Example: Application component unavailable, unexpected exception.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function critical(string|\Stringable $message, array $context = []): void
    {
        $this->log->critical($message, $context);
    }

    /**
     * Runtime errors that do not require immediate action but should typically
     * be logged and monitored.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function error(string|\Stringable $message, array $context = []): void
    {
        $this->log->error($message, $context);
    }

    /**
     * Exceptional occurrences that are not errors.
     *
     * Example: Use of deprecated APIs, poor use of an API, undesirable things
     * that are not necessarily wrong.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function warning(string|\Stringable $message, array $context = []): void
    {
        $this->log->warning($message, $context);
    }

    /**
     * Normal but significant events.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function notice(string|\Stringable $message, array $context = []): void
    {
        $this->log->notice($message, $context);
    }

    /**
     * Interesting events.
     *
     * Example: User logs in, SQL logs.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function info(string|\Stringable $message, array $context = []): void
    {
        $this->log->info($message, $context = []);
    }


    /**
     * Detailed debug information.
     *
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     */
    public function debug(string|\Stringable $message, array $context = []): void
    {
        $this->log->debug($message, $context);
    }

    /**
     * Logs with an arbitrary level.
     *
     * @param mixed   $level
     * @param string|\Stringable $message
     * @param mixed[] $context
     *
     * @return void
     *
     * @throws \Psr\Log\InvalidArgumentException
     */
    public function log($level, string|\Stringable $message, array $context = []): void
    {
        $this->log->log($level, $message, $context); 
    }
}
