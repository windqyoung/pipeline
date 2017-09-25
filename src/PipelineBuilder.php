<?php


namespace Wqy\Pipeline;

class PipelineBuilder
{
    /**
     * 初始请求数据
     */
    private $request;

    /**
     * @var callable[]
     */
    private $pipes;

    public function send($request)
    {
        $this->request = $request;
        return $this;
    }

    public function through($pipes)
    {
        $this->pipes = is_array($pipes) ? $pipes : func_get_args();

        return $this;
    }

    /**
     * @param callable $destination
     * @return mixed
     */
    public function then($destination)
    {
        $pipeline = $this->build($destination);
        return $pipeline($this->request);
    }

    public function build($destination)
    {
        $pipes = $this->pipes;
        $head = $prev = new Pipeline(array_shift($pipes));

        foreach ($pipes as $one) {
            $next = new Pipeline($one);
            $prev->setNext($next);
            $prev = $next;
        }

        $prev->setNext(new Pipeline($destination));

        return $head;
    }
}
