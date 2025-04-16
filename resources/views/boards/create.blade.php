<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl">Create Board</h2>
    </x-slot>

    <div class="p-4">
        <form action="{{ route('boards.store') }}" method="POST">
            @csrf
            <label class="block">Board Title</label>
            <input type="text" name="title" class="border rounded p-2 w-full mt-1" required>
            <button type="submit" class="mt-4 bg-green-500 text-white px-4 py-2 rounded">Create</button>
        </form>
    </div>
</x-app-layout>