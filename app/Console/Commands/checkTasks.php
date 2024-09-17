<?php

namespace App\Console\Commands;

use App\Enums\TaskStatus;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Console\Command;

class checkTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:check-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tasks = Task::all();
        foreach ($tasks as $task) {
            if ((now()->greaterThan(Carbon::create($task->due_date)))
                && ($task->status !=  TaskStatus::ENDWORK->value)
                && ($task->status !=  TaskStatus::STARTTEST->value)
                && ($task->status !=  TaskStatus::ENDTEST->value)
                && ($task->status !=  TaskStatus::ENDED->value)
            ) {
                $task->status = TaskStatus::FALIED->value;
                $task->save();
            }
        }
    }
}
