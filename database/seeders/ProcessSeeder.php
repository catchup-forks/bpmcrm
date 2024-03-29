<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EnvironmentVariable;
use App\Models\Screen;
use App\Models\Process;
use App\Models\ProcessTaskAssignment;
use App\Models\Script;
use App\Models\User;
use App\Providers\WorkflowServiceProvider;
class ProcessSeeder extends Seeder
{

    /**
     * Array of [language => mime-type]
     */
    const mimeTypes = [
        'javascript' => 'application/javascript',
        'lua' => 'application/x-lua',
        'php' => 'application/x-php',
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (glob(database_path('processes') . '/*.bpmn') as $filename) {
            echo 'Creating: ', $filename, "\n";
            $process = factory(Process::class)->make([
                'bpmn' => file_get_contents($filename),
            ]);
            //Load the process title from the the main process of the BPMN definition
            $processes = $process->getDefinitions()->getElementsByTagName('process');
            if ($processes->item(0)) {
                $processDefinition = $processes->item(0)->getBpmnElementInstance();
                if (!empty($processDefinition->getName())) {
                    $process->name = $processDefinition->getName();
                }
            }
            //Or load the process title from the collaboration of the BPMN definition
            $collaborations = $process->getDefinitions()->getElementsByTagName('collaboration');
            if ($collaborations->item(0)) {
                $collaborationDefinition = $collaborations->item(0)->getBpmnElementInstance();
                if (!empty($collaborationDefinition->getName())) {
                    $process->name = $collaborationDefinition->getName();
                }
            }
            $process->save();

            $definitions = $process->getDefinitions();

            //Create scripts from the BPMN process definition
            $scriptTasks = $definitions->getElementsByTagName('scriptTask');
            foreach ($scriptTasks as $scriptTaskNode) {
                $scriptTask = $scriptTaskNode->getBpmnElementInstance();
                //Create a row in the Scripts table
                $script = factory(Script::class)->create([
                    'title' => $scriptTask->getName('name') . ' Script',
                    'code' => $scriptTaskNode->getElementsByTagName('script')->item(0)->nodeValue,
                    'language' => $this->languageOfMimeType($scriptTask->getScriptFormat()),
                ]);
                $scriptTaskNode->setAttributeNS(
                    WorkflowServiceProvider::PROCESS_MAKER_NS, 'scriptRef', $script->id
                );
                $scriptTaskNode->setAttributeNS(
                    WorkflowServiceProvider::PROCESS_MAKER_NS, 'scriptConfiguration', '{}'
                );
            }

            //Add screens to the process
            $tasks = $definitions->getElementsByTagName('task');
            $admin = User::where('username', 'admin')->firstOrFail();
            foreach($tasks as $task) {
                $screenRef = $task->getAttributeNS(WorkflowServiceProvider::PROCESS_MAKER_NS, 'screenRef');
                $id = $task->getAttribute('id');
                if ($screenRef) {
                    $screen = $this->createScreen($id, $screenRef, $process);
                    $task->setAttributeNS(WorkflowServiceProvider::PROCESS_MAKER_NS, 'screenRef', $screen->getKey());
                }
                //Assign "admin" to the task 
                factory(ProcessTaskAssignment::class)->create([
                    'process_id' => $process->getKey(),
                    'process_task_id' => $id,
                    'assignment_id' => $admin->getKey(),
                    'assignment_type' => 'user',
                ]);
            }


            //Update the screen and script references in the BPMN of the process
            $process->bpmn = $definitions->saveXML();
            $process->save();

            echo 'Process created: ', $process->uid, "\n";

            //Create environment variables for the default processes
            factory(EnvironmentVariable::class)->create([
                'name' => 'hours_of_work',
                'description' => 'Regular schedule of hours of work for employees',
                'value' => '8'
            ]);
        }
    }

    /**
     * Load the JSON of a screen.
     *
     * @param string $id
     * @param string $screenRef
     * @param string $process
     *
     * @return Screen
     */
    private function createScreen($id, $screenRef, $process) {

        if (file_exists(database_path('processes/screens/' . $screenRef . '.json'))) {
            $json = json_decode(file_get_contents(database_path('processes/screens/' . $screenRef . '.json')));
            return factory(Screen::class)->create([
                        'title' => $json[0]->name,
                        'config' => $json,
                        'process_id' => $process->id,
            ]);
        } elseif (file_exists(database_path('processes/screens/' . $id . '.json'))) {
            $json = json_decode(file_get_contents(database_path('processes/screens/' . $id . '.json')));
            return factory(Screen::class)->create([
                        'title' => $json[0]->name,
                        'config' => $json,
            ]);
        }
    }

    /**
     * Get the language that corresponds to an specific mime-type.
     *
     * @param string $mime
     *
     * @return string
     */
    private function languageOfMimeType($mime)
    {
        return in_array($mime, self::mimeTypes) ? array_search($mime, self::mimeTypes) : '';
    }
}
