<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Column to "{{ $board->title }}"</h2>
    </x-slot>

    <div class="p-4">
        <form action="{{ route('columns.store', $board) }}" method="POST">
            @csrf
            <label class="block">Column Title</label>
            <input type="text" name="title" class="border rounded p-2 w-full mt-1" required>
            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Add Column</button>
        </form>
    </div>
</x-app-layout>
