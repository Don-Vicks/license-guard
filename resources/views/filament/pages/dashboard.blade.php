<x-filament::page>
    <div class="space-y-6">
        @foreach($this->getWidgets() as $widget)
            @livewire($widget)
        @endforeach
    </div>
</x-filament::page>
