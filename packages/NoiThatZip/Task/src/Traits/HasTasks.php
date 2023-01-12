<?php
declare(strict_types=1);
/*
 * This file is part of Laravel Commentable.
 *
 * (c) Brian Faust <hello@brianfaust.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NoiThatZip\Task\Traits;
use NoiThatZip\Task\Models\Task;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;
trait HasTasks
{
    /**
     * @return string
     */
    public function taskModel(): string
    {
        return config('task.model');
    }
    /**
     * @return mixed
     */
    public function tasks(): MorphMany
    {
        return $this->morphMany($this->taskModel(), 'tasklog');
    }
    /**
     * @param $data
     * @param Model      $creator
     * @param Model|null $parent
     *
     * @return static
     */
    public function task($data, Model $creator, Model $parent = null)
    {
        $taskModel = $this->taskModel();
        $task = (new $taskModel())->createTask($this, $data, $creator);
        if (!empty($parent)) {
            $parent->appendNode($task);
        }
        return $task;
    }
    /**
     * @param $id
     * @param $data
     * @param Model|null $parent
     *
     * @return mixed
     */
    public function updateTask($id, $data, Model $parent = null)
    {
        $taskModel = $this->taskModel();
        $task = (new $taskModel())->updateTask($id, $data);
        if (!empty($parent)) {
            $parent->appendNode($task);
        }
        return $task;
    }
    /**
     * @param $id
     *
     * @return mixed
     */
    public function deleteTask($id): bool
    {
        $taskModel = $this->taskModel();
        return (bool) (new $taskModel())->deleteTask($id);
    }
    /**
     * @return mixed
     */
    public function taskCount(): int
    {
        return $this->tasks->count();
    }
}
