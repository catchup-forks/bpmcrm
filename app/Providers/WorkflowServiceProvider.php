<?php
namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\BpmnEngine;
use App\Listeners\BpmnSubscriber;
use App\Managers\WorkflowManager;
use App\Nayra\Contracts\Storage\BpmnDocumentInterface;
use App\Nayra\Storage\BpmnDocument;
use App\Repositories\DefinitionsRepository;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * app BPMN extension definitions.
     */
    const PROCESS_MAKER_NS = 'http://processmaker.com/BPMN/2.0/Schema.xsd';

    /**
     * The subscriber classes to register.
     *
     * @var array
     */
    protected $subscribe = [
        BpmnSubscriber::class,
    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        /**
         * BPMN Workflow Manager
         */
        $this->app->singleton('workflow.manager', function ($app) {
            return new WorkflowManager();
        });
        /** 
         * BpmnDocument Process Context
         */
        $this->app->bind(BpmnDocumentInterface::class, function ($app, $params) {
            $repository = new DefinitionsRepository();
            $eventBus = app('events');

            //Initialize the BpmnEngine
            $engine = new BpmnEngine($repository, $eventBus);

            //Initialize BpmnDocument repository (REQUIRES $engine $factory)
            $bpmnRepository = new BpmnDocument();
            $bpmnRepository->setEngine($engine);
            $bpmnRepository->setFactory($repository);
            $mapping = $bpmnRepository->getBpmnElementsMapping();
            $engine->setStorage($bpmnRepository);
            $engine->setProcess($params['process']);

            //Initialize custom properties for app
            $bpmnRepository->setBpmnElementMapping(self::PROCESS_MAKER_NS, '', []);
            $bpmnRepository->setBpmnElementMapping(BpmnDocument::BPMN_MODEL, 'userTask', $mapping[BpmnDocument::BPMN_MODEL]['task']);

            return $bpmnRepository;
        });
    }
}
