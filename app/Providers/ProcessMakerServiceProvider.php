<?php

namespace App\Providers;

use Illuminate\Contracts\Auth\Authenticatable;
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
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        parent::boot();
    }

    /**
     * Register our bindings in the service container.
     */
    public function register(): void
    {
        parent::register();
        $this->app->bind('path.public', function (): string {
            return base_path() . '/public_html';
        });

        $this->app->singleton('user.manager', function ($app): UserManager {
            return new UserManager();
        });

        $this->app->singleton('process_file.manager', function ($app): ProcessFileManager {
            return new ProcessFileManager();
        });

        /*$this->app->singleton('process_category.manager', function ($app) {
            return new ProcessCategoryManager();
        });*/

        $this->app->singleton('database.manager', function ($app): DatabaseManager {
            return new DatabaseManager();
        });

        $this->app->singleton('schema.manager', function ($app): SchemaManager {
            return new SchemaManager();
        });

        $this->app->singleton('process.manager', function ($app): ProcessManager {
            return new ProcessManager();
        });

        $this->app->singleton('report_table.manager', function ($app): ReportTableManager {
            return new ReportTableManager();
        });

        $this->app->singleton('task.manager', function ($app): TaskManager {
            return new TaskManager();
        });

        $this->app->singleton('task_assignee.manager', function ($app): TaskAssigneeManager {
            return new TaskAssigneeManager();
        });

        $this->app->singleton('input_document.manager', function ($app): InputDocumentManager {
            return new InputDocumentManager();
        });

        $this->app->singleton('output_document.manager', function ($app): OutputDocumentManager {
            return new OutputDocumentManager();
        });

        $this->app->singleton('task_delegation.manager', function ($app): TasksDelegationManager {
            return new TasksDelegationManager();
        });

        /*
         * Maps our Modeler Manager as a singleton. The Modeler Manager is used
         * to manage customizations to the Process Modeler.
         */
        $this->app->singleton(ModelerManager::class, function ($app): ModelerManager {
            return new ModelerManager();
        });

        /*
         * Maps our Screen Builder Manager as a singleton. The Screen Builder Manager is used
         * to manage customizations to the Screen Builder.
         */
        $this->app->singleton(ScreenBuilderManager::class, function ($app): ScreenBuilderManager {
            return new ScreenBuilderManager();
        });
        // Listen to the events for our core screen types and add our javascript
        Event::listen(ScreenBuilderStarting::class, function ($event): void {
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
        Horizon::auth(function ($request): bool {
            return Auth::user() instanceof Authenticatable;
        });

        // we are using custom passport migrations
        Passport::ignoreMigrations();
    }
}
