<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">📚 Mes QCM</h1>
    </x-slot>

    {{-- Button to generate a new training quiz (available to all users) --}}
    <div class="mb-6">
        <a href="{{ route('knowledge.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            ➕ Générer un QCM d'entraînement
        </a>
    </div>

    {{-- Main content container --}}
    <div class="p-6 space-y-8">

        {{-- Section: Quizzes assigned to the student but not yet completed --}}
        <div>
            <h2 class="text-xl font-semibold mb-2">📌 QCM à faire</h2>

            {{-- Loop through quizzes assigned to the student --}}
            @forelse ($assignedQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Assigné le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>

                    {{-- Link to start answering the quiz --}}
                    <a href="{{ route('knowledge.quiz.answer', $quiz) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                        📝 Répondre
                    </a>
                </div>
            @empty
                {{-- Message if no assigned quizzes --}}
                <p class="text-gray-500 italic">Aucun QCM à faire pour le moment.</p>
            @endforelse
        </div>

        {{-- Section: Quizzes already completed by the student --}}
        <div>
            <h2 class="text-xl font-semibold mb-2">✅ QCM complétés</h2>
            {{-- Loop through quizzes completed by the student --}}
            @forelse ($completedQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Répondu le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>

                    {{-- Display student's score --}}
                    <p class="text-green-700 font-medium">🎯 Score : {{ $quiz->pivot->score }} / {{ $quiz->question_count }}</p>

                    {{-- Link to view the quiz results --}}
                    <a href="{{ route('knowledge.quiz.result', $quiz->id) }}" class="text-blue-600 underline">Voir les réponses</a>
                </div>
            @empty
                {{-- Message if no completed quizzes --}}
                <p class="text-gray-500 italic">Aucun QCM complété pour l’instant.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>


