<x-app-layout>
    <!-- Page header slot -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">👩‍🏫 QCM - Espace Enseignant</h1>
    </x-slot>

    <!-- Button to generate a quiz with AI -->
    <div class="mb-6">
        <a href="{{ route('knowledge.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            ➕ Générer un QCM avec l’IA
        </a>
    </div>

    <!-- Main content container -->
    <div class="p-6 space-y-10">

        <!-- Published quizzes section -->
        <div>
            <h2 class="text-xl font-semibold mb-2 text-green-700">✅ QCM publiés</h2>

            <!-- Loop through all published quizzes -->
            @forelse ($publishedQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Publié le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>

                    <!-- Links to view and assign quiz -->
                    <div class="flex gap-3">
                        <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">👁️ Voir</a>
                        <a href="{{ route('knowledge.assign.quiz.form', ['quiz_id' => $quiz->id]) }}" class="text-indigo-600 underline">📤 Affecter à une cohorte</a>
                    </div>

                    <!-- Delete quiz form -->
                    <form action="{{ route('knowledge.quiz.delete', $quiz->id) }}" method="POST" onsubmit="return confirm('Supprimer ce QCM ?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 underline">🗑 Supprimer</button>
                    </form>
                </div>
            @empty
                <!-- Message shown if no published quizzes -->
                <p class="text-gray-500 italic">Aucun QCM publié.</p>
            @endforelse
        </div>

        <!-- Draft quizzes section -->
        <div>
            <h2 class="text-xl font-semibold mb-2 text-yellow-600">📝 QCM brouillons</h2>

            <!-- Loop through all draft quizzes -->
            @forelse ($draftQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Créé le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>

                    <!-- Links to view and publish quiz -->
                    <div class="flex gap-3">
                        <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">👁️ Voir</a>
                        <a href="{{ route('knowledge.assign.quiz.form', ['quiz_id' => $quiz->id]) }}" class="text-indigo-600 underline">📤 Publier / Affecter</a>
                    </div>

                    <!-- Delete quiz form -->
                    <form action="{{ route('knowledge.quiz.delete', $quiz->id) }}" method="POST" onsubmit="return confirm('Supprimer ce QCM ?')" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 underline">🗑 Supprimer</button>
                    </form>
                </div>
            @empty
                <!-- Message shown if no draft quizzes -->
                <p class="text-gray-500 italic">Aucun QCM en brouillon.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
