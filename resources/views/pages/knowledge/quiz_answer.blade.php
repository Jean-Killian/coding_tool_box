<x-app-layout>
    <!-- Header section displaying the subject of the quiz -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">📝 Répondre au QCM : {{ $subject }}</h1>
    </x-slot>

    <!-- Main container with background and padding -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow space-y-6">

        <!-- Quiz metadata (only if available): number of questions and creation date -->
        @if(isset($quizInfo))
            <div>
                <p><strong>Nombre de questions :</strong> {{ $quizInfo['question_count'] }}</p>
                <p><strong>Date de création :</strong> {{ $quizInfo['created_at']->format('d/m/Y H:i') }}</p>
            </div>
        @endif

        <!-- Quiz submission form -->
        <form method="POST" action="{{ $action }}">
            @csrf

            <!-- Loop through each quiz question -->
            <ol class="space-y-6">
                @foreach ($quizQuestions as $index => $question)
                    <li>
                        <!-- Question title and difficulty level -->
                        <div class="mb-2">
                            <p class="font-semibold">{{ $index + 1 }}. {{ $question['question'] }}</p>
                            <p class="text-sm text-gray-500 italic">
                                Difficulté : {{ ucfirst($question['difficulty'] ?? 'non précisée') }}
                            </p>
                        </div>

                        <!-- List of options as radio buttons -->
                        <div class="space-y-1 ml-4">
                            @foreach ($question['options'] as $option)
                                <label class="block">
                                    <input type="radio" name="answers[{{ $index }}]" value="{{ $option }}" required>
                                    <span class="ml-2">{{ $option }}</span>
                                </label>
                            @endforeach
                        </div>
                    </li>
                @endforeach
            </ol>

            <!-- Submit button aligned to the right -->
            <div class="mt-8 text-right">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    ✅ Soumettre mes réponses
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
