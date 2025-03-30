<x-layouts.app :title="__('busca')">
    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">
        <div class="grid auto-rows-min gap-4 md:grid-cols-3">
            <div class="col-span-2 aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                teste
            </div>
            <div class=" aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">
                teste
            </div>
        </div>
    </div>
    
    <div class="grid grid-flow-col grid-rows-3 gap-4">
        <div class=" row-span-3">01</div>
        <div class="col-span-2">02</div>
        <div class=" col-span-2 row-span-2">03</div>
      </div>
</x-layouts.app>
