<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste de tâches</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8 text-center">Ma Liste de Tâches</h1>
        
        <div class="max-w-md mx-auto bg-white rounded-lg shadow-md overflow-hidden">
            <!-- Formulaire d'ajout -->
            <form action="{{ route('tasks.store') }}" method="POST" class="p-4 border-b">
                @csrf
                <div class="flex">
                    <input type="text" name="title" placeholder="Nouvelle tâche..." 
                           class="flex-grow px-4 py-2 border rounded-l focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-r hover:bg-blue-600">
                        Ajouter
                    </button>
                </div>
            </form>
            
            <!-- Liste des tâches -->
            <ul class="divide-y">
                @foreach($tasks as $task)
                    <li class="p-4 flex items-center justify-between">
                        <div class="flex items-center">
                            <form action="{{ route('tasks.update', $task) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <button type="submit" class="mr-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $task->completed ? 'text-green-500' : 'text-gray-300' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </form>
                            <span class="{{ $task->completed ? 'line-through text-gray-400' : '' }}">
                                {{ $task->title }}
                            </span>
                        </div>
                        <form action="{{ route('tasks.destroy', $task) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
</body>
</html>