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
                            <li class="flex flex-col border-b py-2">
                                <div class="flex justify-between items-center">
                                    <span>{{ $column->title }}</span>
                                    <div class="space-x-2">
                                        <button onclick="openEditColumnModal({{ $board->id }}, {{ $column->id }}, '{{ addslashes($column->title) }}')"
                                                class="text-sm text-blue-500 hover:underline">Edit Column</button>
                                        <button onclick="openDeleteColumnModal({{ $board->id }}, {{ $column->id }}, '{{ addslashes($column->title) }}')"
                                                class="text-sm text-red-500 hover:underline">Delete</button>
                                    </div>
                                </div>

                                <ul class="ml-4 mt-2">
                                    @foreach ($column->tasks as $task)
                                        <li class="flex justify-between border-b py-1">
                                            <div>
                                                <strong>{{ $task->title }}</strong>
                                                @if ($task->due_date)
                                                    <small class="text-gray-500 block">Due: {{ $task->due_date }}</small>
                                                @endif
                                                @if ($task->description)
                                                    <p class="text-gray-600 text-sm">{{ $task->description }}</p>
                                                @endif

                                                @if($task->subtasks->count())
                                                    <ul class="ml-4 mt-2">
                                                        @foreach($task->subtasks as $subtask)
                                                            <li class="flex items-center space-x-2">
                                                                <form action="{{ route('subtasks.toggle', [$board, $column, $task, $subtask]) }}" method="POST" class="flex items-center space-x-2">
                                                                    @csrf
                                                                    @method('PATCH')
                                                                    <input type="checkbox" onchange="this.form.submit()" {{ $subtask->is_completed ? 'checked' : '' }}>
                                                                    <span class="{{ $subtask->is_completed ? 'line-through text-gray-500' : '' }}">{{ $subtask->title }}</span>
                                                                </form>

                                                                <button type="button"
                                                                    onclick="openEditSubtaskModal({{ $board->id }}, {{ $column->id }}, {{ $task->id }}, {{ $subtask->id }}, '{{ addslashes($subtask->title) }}')"
                                                                    class="text-blue-500 text-xs hover:underline ml-2">
                                                                    Edit Subtask
                                                                </button>

                                                                <button type="button"
                                                                    onclick="openDeleteSubtaskModal({{ $board->id }}, {{ $column->id }}, {{ $task->id }}, {{ $subtask->id }}, '{{ addslashes($subtask->title) }}')"
                                                                    class="text-red-500 text-xs hover:underline ml-2">
                                                                    Delete Subtask
                                                                </button>


                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                                <form action="{{ route('subtasks.store', [$board, $column, $task]) }}" method="POST" class="flex items-center space-x-2 mt-2">
                                                    @csrf
                                                    <input type="text" name="title" placeholder="New subtask..." class="border rounded px-2 py-1 text-sm" required>
                                                    <button type="submit" class="bg-blue-500 text-white px-2 py-1 rounded text-sm">Add</button>
                                                </form>
                                                
                                                @if ($errors->subtask->any())
                                                    <div class="text-red-500 text-sm mt-1">
                                                        @foreach ($errors->subtask->all() as $error)
                                                            <p>{{ $error }}</p>
                                                        @endforeach
                                                    </div>
                                                @endif
                                                
                                                

                                            </div>
                                            <div class="space-x-2">
                                                <button onclick="openEditTaskModal({{ $board->id }}, {{ $column->id }}, {{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description ?? '') }}', '{{ $task->due_date }}')"
                                                    class="text-blue-500 text-sm hover:underline">Edit Task</button>
                                                
                                                <button onclick="openDeleteTaskModal({{ $board->id }}, {{ $column->id }}, {{ $task->id }}, '{{ addslashes($task->title) }}')"
                                                        class="text-red-500 text-sm hover:underline">
                                                    Delete Task
                                                </button>
                                                
                                            </div>
                                        </li>
                                    @endforeach

                                    <li class="mt-2">
                                        <button onclick="openCreateTaskModal({{ $board->id }}, {{ $column->id }})"
                                                class="text-green-600 text-sm hover:underline">+ Add Task</button>
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

                <!-- Add Column Modal -->
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
                            window.scrollTo({ top: 0, behavior: 'smooth' });
                        }
                    </script>
                @endif

            @endforeach
        </ul>
    </div>

    @include('boards.partials.modals')
    @include('tasks.partials.modals')

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

        function openCreateTaskModal(boardId, columnId) {
            const modal = document.getElementById('createTaskModal');
            const form = document.getElementById('createTaskForm');
            form.action = `/boards/${boardId}/columns/${columnId}/tasks`;
            document.getElementById('createTaskTitle').value = '';
            document.getElementById('createTaskDescription').value = '';
            document.getElementById('createTaskDueDate').value = '';
            modal.classList.remove('hidden');
        }

        function openEditTaskModal(boardId, columnId, taskId, title, description, dueDate) {
            const modal = document.getElementById('editTaskModal');
            const form = document.getElementById('editTaskForm');

            form.action = `/boards/${boardId}/columns/${columnId}/tasks/${taskId}`;

            document.getElementById('editTaskTitle').value = title;
            document.getElementById('editTaskDescription').value = description;
            document.getElementById('editTaskDueDate').value = dueDate;

            modal.classList.remove('hidden');
        }

        function openDeleteTaskModal(boardId, columnId, taskId, taskTitle) {
            const modal = document.getElementById('deleteTaskModal');
            const form = document.getElementById('deleteTaskForm');
            const titleSpan = document.getElementById('taskToDeleteTitle');

            form.action = `/boards/${boardId}/columns/${columnId}/tasks/${taskId}`;
            titleSpan.textContent = taskTitle;

            modal.classList.remove('hidden');
        }

        function openEditSubtaskModal(boardId, columnId, taskId, subtaskId, subtaskTitle) {
            const modal = document.getElementById('editSubtaskModal');
            const form = document.getElementById('editSubtaskForm');
            const input = document.getElementById('editSubtaskTitle');

            form.action = `/boards/${boardId}/columns/${columnId}/tasks/${taskId}/subtasks/${subtaskId}/edit`;
            input.value = subtaskTitle;

            modal.classList.remove('hidden');
        }

        function openDeleteSubtaskModal(boardId, columnId, taskId, subtaskId, subtaskTitle) {
            const modal = document.getElementById('deleteSubtaskModal');
            const form = document.getElementById('deleteSubtaskForm');
            const titleSpan = document.getElementById('subtaskToDeleteTitle');

            form.action = `/boards/${boardId}/columns/${columnId}/tasks/${taskId}/subtasks/${subtaskId}`;
            titleSpan.textContent = subtaskTitle;

            modal.classList.remove('hidden');
        }



    </script>
</x-app-layout>