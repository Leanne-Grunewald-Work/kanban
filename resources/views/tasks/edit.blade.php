<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Edit Task</h2>
    </x-slot>

    <div class="p-4">
        <form action="{{ route('tasks.update', [$board, $column, $task]) }}" method="POST">
            @csrf
            @method('PUT')

            <label class="block">Title</label>
            <input type="text" name="title" value="{{ $task->title }}" class="w-full border p-2 mb-2" required>

            <label class="block">Description</label>
            <textarea name="description" class="w-full border p-2 mb-2">{{ $task->description }}</textarea>

            <label class="block">Due Date</label>
            <input type="date" name="due_date" value="{{ $task->due_date }}" class="w-full border p-2 mb-2">

            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
        </form>
    </div>
</x-app-layout>
