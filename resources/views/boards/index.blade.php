<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Your Boards</h2>
    </x-slot>

    <div class="p-4">
        <a href="{{ route('boards.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded">New Board</a>

        <ul class="mt-4">
            @foreach($boards as $board)
                <li class="mb-2 p-2 border rounded flex justify-between items-center">
                    <span>{{ $board->title }}</span>
                    <div class="space-x-2">
                        <a href="{{ route('boards.edit', $board) }}" class="text-blue-500">Edit</a>
                        <form action="{{ route('boards.destroy', $board) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('Delete this board?')" class="text-red-500">Delete</button>
                        </form>
                    </div>
                    <ul class="mt-2 ml-4">
                        @foreach($board->columns as $column)
                            <li class="flex justify-between items-center border-b py-1">
                                <span>{{ $column->title }}</span>
                                <div class="space-x-2">
                                    <a href="{{ route('columns.edit', [$board, $column]) }}" class="text-blue-500">Edit</a>
                                    <form action="{{ route('columns.destroy', [$board, $column]) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this column?')" class="text-red-500">Delete</button>
                                    </form>
                                </div>
                                <ul class="ml-4">
                                    @foreach ($column->tasks as $task)
                                        <li class="flex justify-between border-b py-1">
                                            <div>
                                                <strong>{{ $task->title }}</strong>
                                                @if ($task->due_date)
                                                    <small class="text-gray-500 block">Due: {{ $task->due_date }}</small>
                                                @endif
                                            </div>
                                            <div class="space-x-2">
                                                <a href="{{ route('tasks.edit', [$board, $column, $task]) }}" class="text-blue-500">Edit</a>
                                                <form action="{{ route('tasks.destroy', [$board, $column, $task]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button onclick="return confirm('Delete this task?')" class="text-red-500">Delete</button>
                                                </form>
                                            </div>
                                        </li>
                                    @endforeach
                                
                                    <li class="mt-1">
                                        <a href="{{ route('tasks.create', [$board, $column]) }}" class="text-green-600">+ Add Task</a>
                                    </li>
                                </ul>
                                
                            </li>
                        @endforeach
                        <li class="mt-2">
                            <a href="{{ route('columns.create', $board) }}" class="text-green-600">+ Add Column</a>
                        </li>
                    </ul>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>