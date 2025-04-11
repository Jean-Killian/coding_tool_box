<x-app-layout>
    <!-- Page header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">Mes QCM générés</h1>
    </x-slot>

    <!-- Button to generate a new quiz -->
    <div class="mb-6">
        <a href="{{ route('knowledge.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            ➕ Générer un nouveau QCM
        </a>
    </div>

    <!-- List of quizzes -->
    <div class="p-6 space-y-4">
        @foreach ($quizzes as $quiz)
            <!-- Single quiz card -->
            <div class="border p-4 rounded bg-white shadow">
                <!-- Quiz title -->
                <h2 class="font-semibold text-lg">{{ $quiz->subject }}</h2>

                <!-- Number of questions -->
                <p>{{ $quiz->question_count }} question(s)</p>

                <!-- Creation date -->
                <p class="text-sm text-gray-600">Créé le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>

                <!-- Publish status -->
                <p class="mt-1 text-sm {{ $quiz->is_published ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $quiz->is_published ? '✅ Publié' : '📝 Brouillon' }}
                </p>

                <!-- Link to view quiz -->
                <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">Voir</a>
            </div>
        @endforeach
    </div>
</x-app-layout>



