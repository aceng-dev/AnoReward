<?php
$projectRoot = dirname(__DIR__);
require $projectRoot . '/vendor/autoload.php';
$app = require_once $projectRoot . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$u = User::where('role', 'developer')->first();
if (!$u) {
    echo "NO_DEVELOPER_FOUND\n";
    exit(0);
}

echo "DEVELOPER_FOUND\n";
print_r($u->only(['id','name','email','role','total_points','current_salary']));

try {
    $tasks = $u->tasks()->get()->map(function($t){
        return [
            'id' => $t->id,
            'title' => $t->title ?? $t->task_name ?? null,
            'status' => $t->status,
        ];
    })->toArray();
    echo "TASKS:\n";
    print_r($tasks);
} catch (\Exception $e) {
    echo "TASKS_ERROR: " . $e->getMessage() . "\n";
}
