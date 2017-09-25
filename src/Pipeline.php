<?php

namespace Wqy\Pipeline;

class Pipeline
{
    /**
     * @var callable
     */
    private $callable;

    /**
     * 责任链, 下一个
     * @var Pipe
     */
    private $next;

    public function __construct($callable = null, $next = null)
    {
        $this->setCallable($callable);
        $this->setNext($next);
    }

    public function setCallable($callable)
    {
        if (is_null($callable)) {
            $callable = function ($request, $next) {
                return $next($request);
            };
        }
        $this->callable = $callable;
    }

    public function setNext($next)
    {
        if (! is_callable($next)) {
            $next = function ($request) {
                return $request;
            };
        }
        else {
            $next = function ($request) use ($next) {
                return $next($request);
            };
        }
        $this->next = $next;
    }

    public function __invoke($request)
    {
        return call_user_func($this->callable, $request, $this->next);
    }
}
