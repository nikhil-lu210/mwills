<x-layouts::app.sidebar :title="$title ?? null" :breadcrumbs="$breadcrumbs ?? []">
    {{ $slot }}
</x-layouts::app.sidebar>
