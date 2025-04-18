<!-- Create Task Modal -->
<div id="createTaskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Add New Task</h2>

        <form id="createTaskForm" method="POST">
            @csrf

            <input type="hidden" id="taskBoardId" name="board_id">

            <div class="mb-4">
                <label class="block text-sm font-medium">Column</label>
                <select id="taskColumnSelect" name="column_id" class="w-full border rounded px-3 py-2 mt-1" required onchange="updateTaskFormAction()">
                    <option value="">Select column...</option>
                    @foreach($board->columns as $column)
                        <option value="{{ $column->id }}">{{ $column->title }}</option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Task Title</label>
                <input type="text" name="title" id="createTaskTitle" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" id="createTaskDescription" class="w-full border rounded px-3 py-2 mt-1"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Due Date</label>
                <input type="date" name="due_date" id="createTaskDueDate" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            @if ($errors->task->any())
                <div class="text-red-500 text-sm mb-4">
                    @foreach ($errors->task->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('createTaskModal').classList.add('hidden')"
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


@if ($errors->task->any())
    <script>
        window.onload = function () {
            document.getElementById('createTaskModal').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
@endif

<!-- Edit Task Modal -->
@if ($errors->taskUpdate->any())
    <script>
        window.onload = function () {
            openEditTaskModal(
                {{ session('board_id') }},
                {{ session('column_id') }},
                {{ session('task_id') }}
            );
            window.scrollTo({ top: 0, behavior: 'smooth' });
        };
    </script>
@endif

<input type="hidden" id="old_edit_task_title" value="{{ old('title') }}">
<input type="hidden" id="old_edit_task_description" value="{{ old('description') }}">
<input type="hidden" id="old_edit_task_due_date" value="{{ old('due_date') }}">
<div id="editTaskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Task</h2>

        <form id="editTaskForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" id="editTaskTitle" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Description</label>
                <textarea name="description" id="editTaskDescription" class="w-full border rounded px-3 py-2 mt-1"></textarea>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium">Due Date</label>
                <input type="date" name="due_date" id="editTaskDueDate" class="w-full border rounded px-3 py-2 mt-1">
            </div>

            @if ($errors->taskUpdate->any())
                <div class="text-red-500 text-sm mb-4">
                    @foreach ($errors->taskUpdate->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editTaskModal').classList.add('hidden')"
                        class="bg-gray-300 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Delete Task Modal -->
<div id="deleteTaskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4 text-red-600">Delete Task</h2>
        <p class="mb-4 text-gray-700">
            Are you sure you want to delete <span class="font-semibold" id="taskToDeleteTitle"></span>?
            This action cannot be undone.
        </p>

        <form id="deleteTaskForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('deleteTaskModal').classList.add('hidden')"
                        class="bg-gray-300 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Edit Subtask Modal -->
<div id="editSubtaskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Subtask</h2>

        <form id="editSubtaskForm" method="POST">
            @csrf
            @method('PATCH')

            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" id="editSubtaskTitle" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            @if ($errors->subtaskUpdate->any())
                <div class="text-red-500 text-sm mb-4">
                    @foreach ($errors->subtaskUpdate->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editSubtaskModal').classList.add('hidden')"
                        class="bg-gray-300 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Update
                </button>
            </div>
        </form>
    </div>
</div>


<!-- Delete Subtask Modal -->
<div id="deleteSubtaskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4 text-red-600">Delete Subtask</h2>
        <p class="mb-4 text-gray-700">
            Are you sure you want to delete <span class="font-semibold" id="subtaskToDeleteTitle"></span>?
            This action cannot be undone.
        </p>

        <form id="deleteSubtaskForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('deleteSubtaskModal').classList.add('hidden')"
                        class="bg-gray-300 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded">
                    Yes, Delete
                </button>
            </div>
        </form>
    </div>
</div>
