<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">{{ $task->title }}</h2>
    </x-slot>

    <div class="p-4">
        <p><strong>Description:</strong> {{ $task->description }}</p>
        <p><strong>Due Date:</strong> {{ $task->due_date }}</p>
    </div>

    <h3 class="font-bold mb-2 mt-4">Subtasks</h3>

    <ul class="mb-4">
        @foreach($task->subtasks as $subtask)
            <li class="flex items-center justify-between border-b py-1">
                <form action="{{ route('subtasks.toggle', [$board, $column, $task, $subtask]) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <label class="inline-flex items-center">
                        <input type="checkbox" onchange="this.form.submit()" {{ $subtask->is_completed ? 'checked' : '' }}>
                        <span class="ml-2 {{ $subtask->is_completed ? 'line-through text-gray-500' : '' }}">
                            {{ $subtask->title }}
                        </span>
                    </label>
                </form>
                
                <form action="{{ route('subtasks.destroy', [$board, $column, $task, $subtask]) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button class="text-red-500 text-sm" onclick="return confirm('Delete this subtask?')">Delete</button>
                </form>
            </li>
        @endforeach
    </ul>

    <form action="{{ route('subtasks.store', [$board, $column, $task]) }}" method="POST">
        @csrf
        <div class="flex items-center gap-2">
            <input type="text" name="title" placeholder="New subtask..." class="border p-2 w-full">
            <button class="bg-green-500 text-white px-3 py-2 rounded">Add</button>
        </div>
    </form>

</x-app-layout>
