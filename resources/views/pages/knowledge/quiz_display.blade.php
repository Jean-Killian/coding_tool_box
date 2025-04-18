<x-app-layout>
    <!-- Page header with dynamic title based on mode (preview or show) -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">
            {{ $mode === 'preview' ? '🧐 Aperçu du QCM généré' : '🧾 QCM : ' . $quiz['subject'] ?? $quiz->subject }}
        </h1>
    </x-slot>

    <!-- Main container -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow space-y-6">

        <!-- Display quiz metadata depending on the current mode -->
        @if($mode === 'show')
            <p><strong>Nombre de questions :</strong> {{ $quiz->question_count }}</p>
            <p><strong>Date de création :</strong> {{ $quiz->created_at->format('d/m/Y H:i') }}</p>
        @else
            <p><strong>Sujet :</strong> {{ $subject }}</p>
            <p><strong>Nombre de questions :</strong> {{ $questionCount }}</p>
        @endif

        <!-- Display the list of questions and answers -->
        <ol class="space-y-4">
            @foreach(($mode === 'preview' ? $quiz : $quiz->questions) as $i => $question)
                <li>
                    <!-- Question content -->
                    <p class="font-semibold">{{ $i + 1 }}. {{ $question['question'] }}</p>
                    <p class="text-sm text-gray-500 italic">Difficulté : {{ ucfirst($question['difficulty']) }}</p>

                    <!-- Multiple-choice options -->
                    <ul class="list-disc list-inside ml-4">
                        @foreach($question['options'] as $option)
                            <li>{{ $option }}</li>
                        @endforeach
                    </ul>

                    <!-- Correct answer -->
                    <p class="text-green-600 text-sm">✅ Réponse : {{ $question['answer'] }}</p>
                </li>
            @endforeach
        </ol>

        <!-- Form for saving the quiz if in preview mode -->
        @if($mode === 'preview')
            <form action="{{ route('knowledge.store') }}" method="POST" class="space-y-4">
                @csrf
                <!-- Hidden inputs to carry quiz data -->
                <input type="hidden" name="subject" value="{{ $subject }}">
                <input type="hidden" name="question_count" value="{{ $questionCount }}">
                <input type="hidden" name="qcm" value="{{ json_encode($quiz) }}">

                <!-- Buttons to save, regenerate or cancel -->
                <div class="flex gap-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">✅ Enregistrer</button>
                    <a href="{{ route('knowledge.generate') }}" class="bg-yellow-500 text-white px-4 py-2 rounded">🔁 Regénérer</a>
                    <a href="{{ route('knowledge.index') }}" class="bg-gray-400 text-white px-4 py-2 rounded">❌ Annuler</a>
                </div>
            </form>
        @endif

        <!-- Form to assign the quiz to a cohort, only visible to teachers -->
        @if($mode === 'show' && auth()->user()->isTeacher())
            <form method="POST" action="{{ route('knowledge.assign.quiz') }}" class="space-y-2 mt-6">
                @csrf
                <!-- Select cohort to assign quiz to -->
                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">
                <label for="cohort_id">Affecter à une cohorte :</label>
                <select name="cohort_id" required class="border p-2 w-full">
                    <option value="">-- Sélectionner --</option>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                    @endforeach
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">📤 Publier</button>
            </form>
        @endif
    </div>
</x-app-layout>
