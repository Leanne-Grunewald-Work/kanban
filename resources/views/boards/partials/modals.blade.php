<!-- Create Board Modal -->
<div id="boardModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Create New Board</h2>

        <form action="{{ route('boards.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" class="w-full border rounded px-3 py-2 mt-1" value="{{ old('title') }}" required>
            </div>

            @if ($errors->board->any())
                <div class="text-red-500 text-sm mb-4">
                    @foreach ($errors->board->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('boardModal').classList.add('hidden')"
                        class="bg-gray-300 px-4 py-2 rounded">
                    Cancel
                </button>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
                    Create
                </button>
            </div>
        </form>
    </div>
</div>

@if ($errors->board->any())
    <script>
        window.onload = function () {
            document.getElementById('boardModal').classList.remove('hidden');
            window.scrollTo({ top: 50, behavior: 'smooth' });
        }
    </script>
@endif

<!-- Edit Board Modal -->
<div id="editBoardModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Board</h2>

        <form id="editBoardForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">Board Title</label>
                <input type="text" name="title" id="editBoardTitle" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            @if ($errors->boardUpdate->any())
                <div class="text-red-500 text-sm mb-4">
                    @foreach ($errors->boardUpdate->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editBoardModal').classList.add('hidden')"
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


@if ($errors->boardUpdate->any())
    <script>
        window.onload = function () {
            document.getElementById('editBoardModal').classList.remove('hidden');
            window.scrollTo({ top: 50, behavior: 'smooth' });
        }
    </script>
@endif


<!-- Delete Board Modal -->
<div id="deleteBoardModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4 text-red-600">Delete Board</h2>
        <p class="mb-4 text-gray-700">
            Are you sure you want to delete <span class="font-semibold" id="boardToDeleteTitle"></span>?
            This action cannot be undone.
        </p>

        <form id="deleteBoardForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('deleteBoardModal').classList.add('hidden')"
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


<!-- Create Column Modal -->
<div id="createColumnModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Add New Column</h2>

        <form id="createColumnForm" method="POST">
            @csrf

            <div class="mb-4">
                <label class="block text-sm font-medium">Column Title</label>
                <input type="text" name="title" id="createColumnTitle" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            @if ($errors->columnCreate->any())
                <div class="text-red-500 text-sm mb-4">
                    @foreach ($errors->columnCreate->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('createColumnModal').classList.add('hidden')"
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

@if ($errors->columnCreate->any())
    <script>
        window.onload = function () {
            document.getElementById('createColumnModal').classList.remove('hidden');
            window.scrollTo({ top: 0, behavior: 'smooth' });
        }
    </script>
@endif



<!-- Edit Column Modal -->
<div id="editColumnModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4">Edit Column</h2>

        <form id="editColumnForm" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium">Title</label>
                <input type="text" name="title" id="editColumnTitle" class="w-full border rounded px-3 py-2 mt-1" required>
            </div>

            @if ($errors->columnUpdate->any())
                <div class="text-red-500 text-sm mt-1">
                    @foreach ($errors->columnUpdate->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif


            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('editColumnModal').classList.add('hidden')"
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

@if ($errors->columnUpdate->any())
    <script>
        window.onload = function () {
            document.getElementById('editColumnModal').classList.remove('hidden');
            window.scrollTo({ top: 50, behavior: 'smooth' });
        }
    </script>
@endif


<!-- Delete Column Modal -->
<div id="deleteColumnModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden z-50">
    <div class="bg-white p-6 rounded-lg w-96">
        <h2 class="text-xl font-bold mb-4 text-red-600">Delete Column</h2>
        <p class="mb-4 text-gray-700">
            Are you sure you want to delete <span class="font-semibold" id="columnToDeleteTitle"></span>?
            All associated tasks will also be removed.
        </p>

        <form id="deleteColumnForm" method="POST">
            @csrf
            @method('DELETE')

            <div class="flex justify-end space-x-2">
                <button type="button" onclick="document.getElementById('deleteColumnModal').classList.add('hidden')"
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