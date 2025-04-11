<x-app-layout>
    <!-- Page header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">✨ Générer un nouveau QCM avec l'IA</h1>
    </x-slot>

    <!-- Main container -->
    <div class="max-w-2xl mx-auto mt-6 bg-white p-6 rounded shadow space-y-6">

        <!-- Error messages -->
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-4 rounded">
                <strong>Erreur :</strong>
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- QCM generation form -->
        <form method="GET" action="{{ route('knowledge.ia.generate') }}" class="space-y-4">

            <!-- Subject input -->
            <div>
                <label for="subject" class="block font-semibold mb-1">Sujet du QCM</label>
                <input type="text" id="subject" name="subject" required
                       class="w-full border-gray-300 rounded shadow-sm"
                       placeholder="Ex : Laravel, JavaScript, PHP...">
            </div>

            <!-- Question count input -->
            <div>
                <label for="count" class="block font-semibold mb-1">Nombre de questions</label>
                <input type="number" id="count" name="count" min="1" max="10" required
                       class="w-full border-gray-300 rounded shadow-sm"
                       placeholder="Par ex. 5">
            </div>

            <!-- Submit button -->
            <div class="text-right">
                <button type="submit"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded">
                    ⚙️ Générer le QCM
                </button>
            </div>
        </form>

        <!-- Info text -->
        <div class="text-sm text-gray-500 italic">
            L’intelligence artificielle générera un QCM structuré (questions + choix + réponses).
        </div>
    </div>
</x-app-layout>

