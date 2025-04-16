{{--<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">📚 Mes QCM</h1>
    </x-slot>

    <!-- Génération uniquement visible pour les enseignants -->
    <div class="mb-6">
        <a href="{{ route('knowledge.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            ➕ Générer un nouveau QCM
        </a>
    </div>
    <div class="p-6 space-y-4">
        @forelse ($quizzes as $quiz)
            <div class="border p-4 rounded bg-white shadow">
                <h2 class="font-semibold text-lg">{{ $quiz->subject }}</h2>
                <p>{{ $quiz->question_count }} question(s)</p>
                <p class="text-sm text-gray-600">
                    Créé le {{ $quiz->created_at->format('d/m/Y à H:i') }}
                </p>

                <!-- Affichage du statut -->
                <p class="mt-1 text-sm {{ $quiz->is_published ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $quiz->is_published ? '✅ Publié' : '📝 Brouillon' }}
                </p>

                <!-- Lien vers les détails -->
                <a href="{{ route('knowledge.quiz.answer', $quiz) }}" class="text-blue-600 underline">Répondre</a>


                <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">Voir</a>
            </div>
        @empty
            <p class="text-gray-500 italic">Aucun QCM généré pour l’instant.</p>
        @endforelse
    </div>
</x-app-layout>--}}

<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">📚 Mes QCM</h1>
    </x-slot>

    <!-- Génération uniquement visible pour les enseignants -->
    <div class="mb-6">
        <a href="{{ route('knowledge.generate') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            ➕ Générer un QCM d'entraînement
        </a>
    </div>

    <div class="p-6 space-y-8">

        <!-- QCM assignés via une cohorte -->
        <div>
            <h2 class="text-xl font-semibold mb-2">📌 QCM à faire</h2>
            @forelse ($assignedQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Assigné le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>
                    <a href="{{ route('knowledge.quiz.answer', $quiz) }}"
                       class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded text-sm">
                        📝 Répondre
                    </a>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucun QCM à faire pour le moment.</p>
            @endforelse
        </div>

        <!-- QCM déjà complétés par l'étudiant -->
        <div>
            <h2 class="text-xl font-semibold mb-2">✅ QCM complétés</h2>
            @forelse ($completedQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Répondu le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>
                    <p class="text-green-700 font-medium">🎯 Score : {{ $quiz->pivot->score }} / {{ $quiz->question_count }}</p>
                    <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">Voir</a>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucun QCM complété pour l’instant.</p>
            @endforelse
        </div>

        <!-- QCM générés personnellement par l'étudiant -->
        <div>
            <h2 class="text-xl font-semibold mb-2">🧪 QCM générés</h2>
            @forelse ($selfQuizzes as $quiz)
                <div class="border p-4 rounded bg-white shadow space-y-2">
                    <h3 class="text-lg font-semibold">{{ $quiz->subject }}</h3>
                    <p>{{ $quiz->question_count }} questions</p>
                    <p class="text-sm text-gray-500">Créé le {{ $quiz->created_at->format('d/m/Y à H:i') }}</p>
                    <a href="{{ route('knowledge.quiz.show', $quiz) }}" class="text-blue-600 underline">Voir</a>
                </div>
            @empty
                <p class="text-gray-500 italic">Aucun QCM généré personnellement.</p>
            @endforelse
        </div>
    </div>
</x-app-layout>


