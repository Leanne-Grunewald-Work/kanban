<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Your Boards</h2>
    </x-slot>

    <div class="p-4">
        <button onclick="document.getElementById('boardModal').classList.remove('hidden')"
                class="bg-blue-500 text-white px-4 py-2 rounded">
            + New Board
        </button>

        <ul class="mt-4">
            @foreach($boards as $board)
                <li class="mb-2 p-2 border rounded">
                    <div class="flex justify-between items-center">
                        <span>{{ $board->title }}</span>
                        <div class="space-x-2">
                            <button onclick="openEditModal({{ $board->id }}, '{{ addslashes($board->title) }}')"
                                    class="text-blue-500 hover:underline text-sm">Edit Board</button>
                            <button onclick="openDeleteModal({{ $board->id }}, '{{ addslashes($board->title) }}')"
                                    class="text-red-500 hover:underline text-sm">Delete Board</button>
                        </div>
                    </div>

                    <ul class="mt-2 ml-4">
                        @foreach($board->columns as $column)
                            <li class="flex justify-between items-center border-b py-1">
                                <span>{{ $column->title }}</span>
                                <div class="space-x-2">
                                    <button
                                        onclick="openEditColumnModal({{ $board->id }}, {{ $column->id }}, '{{ addslashes($column->title) }}')"
                                        class="text-sm text-blue-500 hover:underline">
                                        Edit Column
                                    </button>

                                    <button onclick="openDeleteColumnModal({{ $board->id }}, {{ $column->id }}, '{{ addslashes($column->title) }}')"
                                        class="text-sm text-red-500 hover:underline">
                                        Delete
                                    </button>
                                    
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

                        <button onclick="document.getElementById('addColumnModal-{{ $board->id }}').classList.remove('hidden')"
                                class="bg-blue-500 text-white px-4 py-2 rounded mt-2">
                            + Add Column
                        </button>
                    </ul>
                </li>

                <!-- Add Column Modal for this Board -->
                <div id="addColumnModal-{{ $board->id }}" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
                    <div class="bg-white p-6 rounded-lg w-96">
                        <h2 class="text-xl font-bold mb-4">Add Column</h2>

                        <form action="{{ route('columns.store', ['board' => $board->id]) }}" method="POST">
                            @csrf

                            <div class="mb-4">
                                <label class="block text-sm font-medium">Title</label>
                                <input type="text" name="title" class="w-full border rounded px-3 py-2 mt-1" value="{{ old('title') }}">
                            </div>

                            @if ($errors->column->any())
                                <div class="text-red-500 text-sm mb-4">
                                    @foreach ($errors->column->all() as $error)
                                        <p>{{ $error }}</p>
                                    @endforeach
                                </div>
                            @endif

                            <div class="flex justify-end space-x-2">
                                <button type="button" onclick="document.getElementById('addColumnModal-{{ $board->id }}').classList.add('hidden')"
                                        class="bg-gray-300 px-4 py-2 rounded">
                                    Cancel
                                </button>
                                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                                    Add
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if ($errors->column->any())
                    <script>
                        window.onload = function() {
                            document.getElementById('addColumnModal-{{ $board->id }}').classList.remove('hidden');
                            window.scrollTo({ top: 50, behavior: 'smooth' });
                        }
                    </script>
                @endif

            @endforeach
        </ul>
    </div>

    @include('boards.partials.modals')

    <script>
        function openEditModal(boardId, boardTitle) {
            const modal = document.getElementById('editBoardModal');
            const form = document.getElementById('editBoardForm');
            const input = document.getElementById('editBoardTitle');
            input.value = boardTitle;
            form.action = `/boards/${boardId}`;
            modal.classList.remove('hidden');
        }

        function openDeleteModal(boardId, boardTitle) {
            const modal = document.getElementById('deleteBoardModal');
            const form = document.getElementById('deleteBoardForm');
            const titleSpan = document.getElementById('boardToDeleteTitle');
            form.action = `/boards/${boardId}`;
            titleSpan.textContent = boardTitle;
            modal.classList.remove('hidden');
        }

        function openEditColumnModal(boardId, columnId, columnTitle) {
            const modal = document.getElementById('editColumnModal');
            const form = document.getElementById('editColumnForm');
            const input = document.getElementById('editColumnTitle');

            input.value = columnTitle;
            form.action = `/boards/${boardId}/columns/${columnId}`;

            modal.classList.remove('hidden');
        }

        function openDeleteColumnModal(boardId, columnId, columnTitle) {
            const modal = document.getElementById('deleteColumnModal');
            const form = document.getElementById('deleteColumnForm');
            const titleSpan = document.getElementById('columnToDeleteTitle');

            form.action = `/boards/${boardId}/columns/${columnId}`;
            titleSpan.textContent = columnTitle;

            modal.classList.remove('hidden');
        }
    </script>
</x-app-layout>