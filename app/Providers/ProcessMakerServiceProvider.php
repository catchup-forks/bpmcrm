<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Schema;
use Laravel\Horizon\Horizon;
use Laravel\Passport\Passport;
use App\Events\ScreenBuilderStarting;
use App\Managers\DatabaseManager;
use App\Managers\InputDocumentManager;
use App\Managers\ModelerManager;
use App\Managers\OutputDocumentManager;
use App\Managers\ProcessCategoryManager;
use App\Managers\ProcessFileManager;
use App\Managers\ProcessManager;
use App\Managers\ReportTableManager;
use App\Managers\SchemaManager;
use App\Managers\ScreenBuilderManager;
use App\Managers\TaskAssigneeManager;
use App\Managers\TaskManager;
use App\Managers\TasksDelegationManager;
use App\Managers\UserManager;

/**
 * Provide our app specific services.
 */
class ProcessMakerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap app services.
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        parent::boot();
    }

    /**
     * Register our bindings in the service container.
     */
    public function register()
    {
        $this->app->bind('path.public', function () {
            return base_path() . '/public_html';
        });

        $this->app->singleton('user.manager', function ($app) {
            return new UserManager();
        });

        $this->app->singleton('process_file.manager', function ($app) {
            return new ProcessFileManager();
        });

        $this->app->singleton('process_category.manager', function ($app) {
            return new ProcessCategoryManager();
        });

        $this->app->singleton('database.manager', function ($app) {
            return new DatabaseManager();
        });

        $this->app->singleton('schema.manager', function ($app) {
            return new SchemaManager();
        });

        $this->app->singleton('process.manager', function ($app) {
            return new ProcessManager();
        });

        $this->app->singleton('report_table.manager', function ($app) {
            return new ReportTableManager();
        });

        $this->app->singleton('task.manager', function ($app) {
            return new TaskManager();
        });

        $this->app->singleton('task_assignee.manager', function ($app) {
            return new TaskAssigneeManager();
        });

        $this->app->singleton('input_document.manager', function ($app) {
            return new InputDocumentManager();
        });

        $this->app->singleton('output_document.manager', function ($app) {
            return new OutputDocumentManager();
        });

        $this->app->singleton('task_delegation.manager', function ($app) {
            return new TasksDelegationManager();
        });

        /*
         * Maps our Modeler Manager as a singleton. The Modeler Manager is used
         * to manage customizations to the Process Modeler.
         */
        $this->app->singleton(ModelerManager::class, function ($app) {
            return new ModelerManager();
        });

        /*
         * Maps our Screen Builder Manager as a singleton. The Screen Builder Manager is used
         * to manage customizations to the Screen Builder.
         */
        $this->app->singleton(ScreenBuilderManager::class, function ($app) {
            return new ScreenBuilderManager();
        });
        // Listen to the events for our core screen types and add our javascript
        Event::listen(ScreenBuilderStarting::class, function ($event) {
            switch ($event->type) {
                case 'FORM':
                    $event->manager->addScript(mix('js/processes/screen-builder/typeForm.js'));
                    break;
                case 'DISPLAY':
                    $event->manager->addScript(mix('js/processes/screen-builder/typeDisplay.js'));
                    break;
            }
        });

        //Enable
        Horizon::auth(function ($request) {
            return !empty(Auth::user());
        });

        // we are using custom passport migrations
        Passport::ignoreMigrations();
    }
}
