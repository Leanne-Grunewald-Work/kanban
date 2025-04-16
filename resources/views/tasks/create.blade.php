<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Add Task to "{{ $column->title }}"</h2>
    </x-slot>

    <div class="p-4">
        <form action="{{ route('tasks.store', [$board, $column]) }}" method="POST">
            @csrf
            <label class="block">Title</label>
            <input type="text" name="title" class="w-full border p-2 mb-2" required>

            <label class="block">Description</label>
            <textarea name="description" class="w-full border p-2 mb-2"></textarea>

            <label class="block">Due Date</label>
            <input type="date" name="due_date" class="w-full border p-2 mb-2">

            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Add Task</button>
        </form>
    </div>
</x-app-layout>