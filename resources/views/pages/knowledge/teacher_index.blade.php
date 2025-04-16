<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">👩‍🏫 QCM - Espace Enseignant</h1>
    </x-slot>

    <!-- Bouton de création -->
    <div class="mb-6">
        <a href="{{ route('knowledge.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            ➕ Générer un QCM avec l’IA
        </a>
    </div>

    <div class="p-6 space-y-10">

        <!-- QCM publiés -->
        <div>
            <h2 class="text-xl font-semibold mb-2 text-green-700">✅ QCM publiés</h2>
            @forelse ($publishedQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Publié le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>

                    <div class="flex gap-3">
                        <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">👁️ Voir</a>
                        <a href="{{ route('knowledge.assign.quiz.form') }}" class="text-indigo-600 underline">📤 Affecter à une cohorte</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucun QCM publié.</p>
            @endforelse
        </div>

        <!-- QCM en brouillon -->
        <div>
            <h2 class="text-xl font-semibold mb-2 text-yellow-600">📝 QCM brouillons</h2>
            @forelse ($draftQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Créé le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>

                    <div class="flex gap-3">
                        <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">👁️ Voir</a>
                        <a href="{{ route('knowledge.assign.quiz.form') }}" class="text-indigo-600 underline">📤 Publier / Affecter</a>
                    </div>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucun QCM en brouillon.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>
