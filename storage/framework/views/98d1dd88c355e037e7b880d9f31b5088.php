<?php

use Livewire\Volt\Actions;
use Livewire\Volt\CompileContext;
use Livewire\Volt\Contracts\Compiled;
use Livewire\Volt\Component;

new class extends Component implements Livewire\Volt\Contracts\FunctionalComponent
{
    public static CompileContext $__context;

    use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

    public $showModal;

    public $selectedImage;

    public function mount()
    {
        (new Actions\InitializeState)->execute(static::$__context, $this, get_defined_vars());

        (new Actions\CallHook('mount'))->execute(static::$__context, $this, get_defined_vars());
    }

    #[\Livewire\Attributes\Computed()]
    public function posts()
    {
        $arguments = [static::$__context, $this, func_get_args()];

        return (new Actions\CallMethod('posts'))->execute(...$arguments);
    }

    public function openModal($image)
    {
        $arguments = [static::$__context, $this, func_get_args()];

        return (new Actions\CallMethod('openModal'))->execute(...$arguments);
    }

    public function closeModal()
    {
        $arguments = [static::$__context, $this, func_get_args()];

        return (new Actions\CallMethod('closeModal'))->execute(...$arguments);
    }

};