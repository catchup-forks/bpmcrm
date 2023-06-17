<?php

namespace App;

use App\Nayra\Contracts\Engine\EngineInterface;
use App\Nayra\Contracts\EventBusInterface;
use App\Nayra\Contracts\RepositoryInterface;
use App\Nayra\Engine\EngineTrait;
use App\Models\Process;
/**
 * Test implementation for EngineInterface.
 *
 * @package app
 */
class BpmnEngine implements EngineInterface
{
    use EngineTrait;

    /**
     * @var RepositoryFactoryInterface
     */
    private $repository;

    /**
     * @var EventBusInterface $dispatcher
     */
    private $dispatcher;

    /**
     * Process definition row.
     *
     * @var \app\Model\Process
     */
    private $process;

    /**
     * Test engine constructor.
     *
     * @param RepositoryInterface $repository
     * @param EventBusInterface $dispatcher
     */
    public function __construct(RepositoryInterface $repository, $dispatcher)
    {
        $this->repository = $repository;
        $this->dispatcher = $dispatcher;
    }

    /**
     * @return EventBusInterface
     */
    public function getDispatcher()
    {
        return $this->dispatcher;
    }

    /**
     * @param EventBusInterface $dispatcher
     *
     * @return $this
     */
    public function setDispatcher(EventBusInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
        return $this;
    }

    /**
     * @return RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param RepositoryInterface $repository
     *
     * @return $this
     */
    public function setRepository(RepositoryInterface $repository)
    {
        $this->repository = $repository;
        return $this;
    }

    /**
     * @return Model\Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param RepositoryInterface $repository
     *
     * @return $this
     */
    public function setProcess(Process $process)
    {
        $this->process = $process;
        return $this;
    }
}
