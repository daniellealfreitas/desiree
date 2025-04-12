<?php

use Livewire\Volt\Actions;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiled;
use Livewire\Volt\Component;

new class extends Component implements Livewire\Volt\Contracts\FunctionalComponent
{
    public static CompileContext $__context;

    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public $limit;

    public $newComment;

    public $posts;

    public function mount()
    {
        (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

        (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
    }

    public function loadMore()
    {
        $arguments = [static::$__context, $this, func_get_args()];

        return (new Actions\CallMethod('loadMore'))->execute(...$arguments);
    }

    public function toggleLike($postId)
    {
        $arguments = [static::$__context, $this, func_get_args()];

        return (new Actions\CallMethod('toggleLike'))->execute(...$arguments);
    }

    public function addComment($postId)
    {
        $arguments = [static::$__context, $this, func_get_args()];

        return (new Actions\CallMethod('addComment'))->execute(...$arguments);
    }

};