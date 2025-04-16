<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Column in "{{ $board->title }}"</h2>
    </x-slot>

    <div class="p-4">
        <form action="{{ route('columns.update', [$board, $column]) }}" method="POST">
            @csrf
            @method('PUT')
            <label class="block">Column Title</label>
            <input type="text" name="title" value="{{ $column->title }}" class="border rounded p-2 w-full mt-1" required>
            <button type="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>