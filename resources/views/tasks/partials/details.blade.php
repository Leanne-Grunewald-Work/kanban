<!-- Task Details Modal -->
<div id="taskDetailsModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-[600px] max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h2 id="taskDetailsTitle" class="text-2xl font-bold">Task Title</h2>
            <button onclick="closeTaskDetailsModal()" class="text-gray-500 hover:text-gray-700">&times;</button>
        </div>

        <div class="mb-4">
            <p id="taskDetailsDescription" class="text-gray-700"></p>
            <p id="taskDetailsDueDate" class="text-gray-500 text-sm mt-2"></p>
        </div>

        <hr class="my-4">

        <div class="mb-4">
            <h3 class="text-lg font-semibold mb-2">Subtasks</h3>
            <ul id="taskDetailsSubtasks" class="space-y-2">
                <!-- Subtasks will be populated dynamically -->
            </ul>

            <form id="addSubtaskForm" method="POST" class="flex mt-4">
                @csrf
                <input type="text" name="title" id="newSubtaskTitle" placeholder="New subtask..."
                       class="w-full border rounded px-3 py-2 text-sm">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded ml-2">
                    Add
                </button>
            </form>

            <div id="subtaskError" class="text-red-500 text-sm mt-2 hidden"></div>
        </div>
    </div>
</div>

<script>
    let currentBoardId = null;
    let currentColumnId = null;
    let currentTaskId = null;

    async function openTaskDetailsModal(boardId, columnId, taskId, title, description, dueDate, subtasksJson = null) {
        currentBoardId = boardId;
        currentColumnId = columnId;
        currentTaskId = taskId;

        document.getElementById('taskDetailsTitle').textContent = title;
        document.getElementById('taskDetailsDescription').textContent = description || 'No description';
        document.getElementById('taskDetailsDueDate').textContent = dueDate ? `Due: ${dueDate}` : '';

        const subtasksContainer = document.getElementById('taskDetailsSubtasks');
        subtasksContainer.innerHTML = '';

        // Always fetch fresh subtasks
        try {
            const response = await fetch(`/boards/${boardId}/columns/${columnId}/tasks/${taskId}/subtasks`);
            if (!response.ok) {
                throw new Error('Failed to fetch subtasks.');
            }

            const data = await response.json();
            latestSubtasks = data.subtasks;

            latestSubtasks.forEach(subtask => {
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

                const editButton = document.createElement('button');
                editButton.classList.add('text-blue-400', 'hover:text-blue-600', 'text-xs');
                editButton.innerHTML = '✏️';
                editButton.onclick = () => openEditSubtaskModal(subtask.id, subtask.title);

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

        } catch (error) {
            alert(error.message);
        }

        document.getElementById('addSubtaskForm').action = `/boards/${boardId}/columns/${columnId}/tasks/${taskId}/subtasks`;

        document.getElementById('taskDetailsModal').classList.remove('hidden');
    }







    function closeTaskDetailsModal() {
        document.getElementById('taskDetailsModal').classList.add('hidden');

        // Update the subtask summary on the board view
        const totalSubtasks = latestSubtasks.length;
        const completedSubtasks = latestSubtasks.filter(subtask => subtask.is_completed).length;

        const summaryElement = document.getElementById(`task-summary-${currentTaskId}`);
        if (summaryElement) {
            summaryElement.textContent = `${completedSubtasks} of ${totalSubtasks} subtasks`;
        }

        latestSubtasks = []; // reset after closing
    }



    document.getElementById('addSubtaskForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const title = document.getElementById('newSubtaskTitle').value.trim();
        const errorDiv = document.getElementById('subtaskError');
        errorDiv.classList.add('hidden');
        errorDiv.textContent = '';

        if (!title) {
            errorDiv.textContent = 'Subtask title is required.';
            errorDiv.classList.remove('hidden');
            return;
        }

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify({ title }),
            });

            if (!response.ok) {
                throw new Error('Failed to add subtask.');
            }

            const data = await response.json(); // expecting a fresh updated list of subtasks

            // Re-render subtasks
            refreshSubtaskList(data.subtasks);

            // Clear input
            document.getElementById('newSubtaskTitle').value = '';

        } catch (error) {
            errorDiv.textContent = error.message;
            errorDiv.classList.remove('hidden');
        }
    });

</script>
