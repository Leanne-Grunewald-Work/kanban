<x-app-layout>
    <div class="flex h-screen overflow-hidden">

        <!-- Sidebar -->
        <aside class="w-64 bg-gray-900 text-white flex flex-col">
            <div class="p-6 font-bold text-xl">
                Kanban
            </div>

            <nav class="flex-1 overflow-y-auto">
                <ul>
                    @foreach($boards as $sidebarBoard)
                        <li class="flex items-center justify-between px-6 py-2 hover:bg-gray-700 {{ $sidebarBoard->id == $board->id ? 'bg-gray-700' : '' }}">
                            <a href="{{ route('boards.show', $sidebarBoard->id) }}" class="flex-1">
                                {{ $sidebarBoard->title }}
                            </a>
            
                            <div class="flex items-center space-x-2">
                                <button onclick="openEditModal({{ $sidebarBoard->id }}, '{{ addslashes($sidebarBoard->title) }}')"
                                        class="text-blue-400 hover:text-blue-600 text-xs">
                                    ✏️
                                </button>
                                <button onclick="openDeleteModal({{ $sidebarBoard->id }}, '{{ addslashes($sidebarBoard->title) }}')"
                                        class="text-red-400 hover:text-red-600 text-xs">
                                    🗑️
                                </button>
                            </div>
                        </li>
                    @endforeach
                    <li>
                        <a href="#" onclick="document.getElementById('boardModal').classList.remove('hidden')"
                           class="block px-6 py-2 text-blue-400 hover:text-blue-600">+ Create New Board</a>
                    </li>
                </ul>
            </nav>
            
        </aside>

        <!-- Main Content -->
        <main class="flex-1 bg-gray-100 overflow-x-auto p-6">
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-2xl font-bold">{{ $board->title }}</h1>
            
                <button onclick="openCreateTaskModal({{ $board->id }})"
                        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded text-sm">
                    + New Task
                </button>
            </div>
            
            

            <div class="flex space-x-6">
                @foreach($board->columns as $column)
                    <div class="bg-white rounded-lg shadow-md w-80 flex-shrink-0">
                        <div class="flex justify-between items-center p-4 border-b">
                            <h2 class="font-semibold text-lg">{{ $column->title }}</h2>
                        
                            <div class="flex items-center space-x-2">
                                <button onclick="openEditColumnModal({{ $board->id }}, {{ $column->id }}, '{{ addslashes($column->title) }}')"
                                        class="text-blue-400 hover:text-blue-600 text-xs">✏️</button>
                                <button onclick="openDeleteColumnModal({{ $board->id }}, {{ $column->id }}, '{{ addslashes($column->title) }}')"
                                        class="text-red-400 hover:text-red-600 text-xs">🗑️</button>
                            </div>
                        </div>
                        

                        <div class="p-4 space-y-4">
                            @foreach($column->tasks as $task)
                                <div class="bg-gray-50 p-3 rounded shadow-sm">
                                    <div class="font-semibold flex justify-between items-center">
                                        <div onclick="openTaskDetailsModal({{ $board->id }}, {{ $column->id }}, {{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description ?? '') }}', '{{ $task->due_date }}', '{{ $task->subtasks->toJson() }}')"
                                            class="font-semibold cursor-pointer hover:underline">
                                            {{ $task->title }}
                                        </div>
                                    
                                        <div class="flex space-x-2">
                                            <button onclick="openEditTaskModal({{ $board->id }}, {{ $column->id }}, {{ $task->id }}, '{{ addslashes($task->title) }}', '{{ addslashes($task->description ?? '') }}', '{{ $task->due_date ?? '' }}')"
                                                class="text-blue-400 text-xs hover:text-blue-600">✏️</button>
                                    
                                            <button onclick="openDeleteTaskModal({{ $board->id }}, {{ $column->id }}, {{ $task->id }}, '{{ addslashes($task->title) }}')"
                                                class="text-red-400 text-xs hover:text-red-600">🗑️</button>
                                        </div>
                                    </div>
                                    
                                    

                                    @if($task->subtasks->count())
                                        <div id="task-summary-{{ $task->id }}" class="text-sm text-gray-500 mt-1">
                                            {{ $task->subtasks->where('is_completed', true)->count() }} of {{ $task->subtasks->count() }} subtasks
                                        </div>
                                    @endif

                                </div>
                            @endforeach

                            <button onclick="openCreateTaskModal({{ $board->id }}, {{ $column->id }})"
                                    class="text-sm text-blue-500 hover:underline">+ Add Task</button>
                        </div>
                    </div>
                @endforeach

                <!-- Add New Column -->
                <button onclick="openCreateColumnModal({{ $board->id }})"
                    class="flex items-center justify-center w-80 h-32 bg-gray-200 rounded-lg hover:bg-gray-300">
                    + New Column
                </button>
                
            </div>
        </main>

    </div>


    


    @include('boards.partials.modals')
    @include('tasks.partials.modals')
    @include('tasks.partials.details')

    <script>

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


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

            titleSpan.textContent = boardTitle;
            form.action = `/boards/${boardId}`;

            modal.classList.remove('hidden');
        }

        function openCreateColumnModal(boardId) {
            const modal = document.getElementById('createColumnModal');
            const form = document.getElementById('createColumnForm');

            form.action = `/boards/${boardId}/columns`;
            document.getElementById('createColumnTitle').value = '';

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

            titleSpan.textContent = columnTitle;
            form.action = `/boards/${boardId}/columns/${columnId}`;

            modal.classList.remove('hidden');
        }

        function openCreateTaskModal(boardId) {
            const modal = document.getElementById('createTaskModal');
            const form = document.getElementById('createTaskForm');

            form.action = '';
            document.getElementById('createTaskTitle').value = '';
            document.getElementById('createTaskDescription').value = '';
            document.getElementById('createTaskDueDate').value = '';
            document.getElementById('taskBoardId').value = boardId;

            modal.classList.remove('hidden');
        }

        function openEditTaskModal(boardId, columnId, taskId, title = '', description = '', dueDate = '') {
            const modal = document.getElementById('editTaskModal');
            const form = document.getElementById('editTaskForm');

            form.action = `/boards/${boardId}/columns/${columnId}/tasks/${taskId}`;

            // Check if hidden fields exist and have values
            const oldTitle = document.getElementById('old_edit_task_title')?.value;
            const oldDescription = document.getElementById('old_edit_task_description')?.value;
            const oldDueDate = document.getElementById('old_edit_task_due_date')?.value;

            document.getElementById('editTaskTitle').value = oldTitle || title;
            document.getElementById('editTaskDescription').value = oldDescription || description;
            document.getElementById('editTaskDueDate').value = oldDueDate || dueDate;

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

        function updateTaskFormAction() {
            const boardId = document.getElementById('taskBoardId').value;
            const columnId = document.getElementById('taskColumnSelect').value;
            const form = document.getElementById('createTaskForm');

            if (columnId) {
                form.action = `/boards/${boardId}/columns/${columnId}/tasks`;
            } else {
                form.action = '';
            }
        }

        let latestSubtasks = [];

        function refreshSubtaskList(subtasks) {
            latestSubtasks = subtasks; // Save globally

            const subtasksContainer = document.getElementById('taskDetailsSubtasks');
            subtasksContainer.innerHTML = '';

            subtasks.forEach(subtask => {
                const li = document.createElement('li');
                li.classList.add('flex', 'items-center', 'space-x-2');

                const checkbox = document.createElement('input');
                checkbox.type = 'checkbox';
                checkbox.checked = subtask.is_completed;
                checkbox.addEventListener('change', () => toggleSubtask(subtask.id));

                const span = document.createElement('span');
                span.textContent = subtask.title;
                if (subtask.is_completed) {
                    span.classList.add('line-through', 'text-gray-500');
                }

                // Edit button
                const editButton = document.createElement('button');
                editButton.classList.add('text-blue-400', 'hover:text-blue-600', 'text-xs');
                editButton.innerHTML = '✏️';
                editButton.onclick = () => openEditSubtaskModal(subtask.id, subtask.title);

                // Delete button
                const deleteButton = document.createElement('button');
                deleteButton.classList.add('text-red-400', 'hover:text-red-600', 'text-xs');
                deleteButton.innerHTML = '🗑️';
                deleteButton.onclick = () => openDeleteSubtaskModal(subtask.id, subtask.title);

                li.appendChild(checkbox);
                li.appendChild(span);
                li.appendChild(editButton);
                li.appendChild(deleteButton);

                subtasksContainer.appendChild(li);
            });
        }




        async function toggleSubtask(subtaskId) {
        try {
            const response = await fetch(`/boards/${currentBoardId}/columns/${currentColumnId}/tasks/${currentTaskId}/subtasks/${subtaskId}`, {
                method: 'PATCH', 
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken, // This should match meta tag
                }
            });

            if (!response.ok) {
                throw new Error('Failed to toggle subtask.');
            }

            const data = await response.json();
            latestSubtasks = data.subtasks; 
            refreshSubtaskList(data.subtasks);
        } catch (error) {
            alert(error.message);
        }
    }

    function openEditSubtaskModal(subtaskId, subtaskTitle) {
        const modal = document.getElementById('editSubtaskModal');
        const form = document.getElementById('editSubtaskForm');
        const input = document.getElementById('editSubtaskTitle');

        input.value = subtaskTitle;
        form.action = `/boards/${currentBoardId}/columns/${currentColumnId}/tasks/${currentTaskId}/subtasks/${subtaskId}/edit`;

        modal.classList.remove('hidden');
    }

    function openDeleteSubtaskModal(subtaskId, subtaskTitle) {
        const modal = document.getElementById('deleteSubtaskModal');
        const form = document.getElementById('deleteSubtaskForm');
        const titleSpan = document.getElementById('subtaskToDeleteTitle');

        titleSpan.textContent = subtaskTitle;
        form.action = `/boards/${currentBoardId}/columns/${currentColumnId}/tasks/${currentTaskId}/subtasks/${subtaskId}`;

        modal.classList.remove('hidden');
    }


    document.getElementById('editSubtaskForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const url = form.action;
        const title = document.getElementById('editSubtaskTitle').value.trim();
        
        if (!title) {
            alert('Title cannot be empty.');
            return;
        }

        try {
            const response = await fetch(url, {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
                body: JSON.stringify({ title })
            });

            if (!response.ok) {
                throw new Error('Failed to update subtask.');
            }

            const data = await response.json(); // Expecting fresh list
            latestSubtasks = data.subtasks;
            refreshSubtaskList(data.subtasks);

            document.getElementById('editSubtaskModal').classList.add('hidden');

            openTaskDetailsModal(
                currentBoardId, 
                currentColumnId, 
                currentTaskId, 
                document.getElementById('taskDetailsTitle').textContent,
                document.getElementById('taskDetailsDescription').textContent,
                document.getElementById('taskDetailsDueDate').textContent.replace('Due: ', ''),
                null
            );

        } catch (error) {
            alert(error.message);
        }
    });



    document.getElementById('deleteSubtaskForm').addEventListener('submit', async function(e) {
        e.preventDefault();

        const form = e.target;
        const url = form.action;

        try {
            const response = await fetch(url, {
                method: 'DELETE',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                }
            });

            if (!response.ok) {
                throw new Error('Failed to delete subtask.');
            }

            const data = await response.json();
            latestSubtasks = data.subtasks; 
            refreshSubtaskList(latestSubtasks);

            document.getElementById('deleteSubtaskModal').classList.add('hidden');

            await fetchTaskDetails(); 

            // After fetching, reopen the task modal
            openTaskDetailsModal(
                currentBoardId,
                currentColumnId,
                currentTaskId,
                document.getElementById('taskDetailsTitle').textContent,
                document.getElementById('taskDetailsDescription').textContent,
                document.getElementById('taskDetailsDueDate').textContent.replace('Due: ', ''),
                null
            );

        } catch (error) {
            alert(error.message);
        }
    });



    async function fetchTaskDetails() {
        try {
            const response = await fetch(`/boards/${currentBoardId}/columns/${currentColumnId}/tasks/${currentTaskId}/subtasks`);
            if (!response.ok) {
                throw new Error('Failed to fetch task subtasks.');
            }

            const data = await response.json();
            latestSubtasks = data.subtasks;

            refreshSubtaskList(latestSubtasks);
        } catch (error) {
            alert(error.message);
        }
    }


















    </script>
</x-app-layout>
