<x-app-layout>
    <!-- Header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">📝 Répondre au QCM : {{ $quiz->subject }}</h1>
    </x-slot>

    <!-- Main content -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow space-y-6">

        <!-- Quiz info -->
        <div>
            <p><strong>Nombre de questions :</strong> {{ $quiz->question_count }}</p>
            <p><strong>Date de création :</strong> {{ $quiz->created_at->format('d/m/Y H:i') }}</p>
        </div>

        <!-- Form to submit answers -->
        <form action="{{ route('knowledge.quiz.submit', $quiz) }}" method="POST">
            @csrf
            <ol class="space-y-6">
                @foreach ($quiz->questions as $index => $question)
                    <li>
                        <div class="mb-2">
                            <p class="font-semibold">{{ $index + 1 }}. {{ $question['question'] }}</p>
                            <p class="text-sm text-gray-500 italic">Difficulté : {{ $question['difficulty'] ?? 'N/A' }}</p>
                        </div>

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

            <!-- Submit button -->
            <div class="mt-8 text-right">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    ✅ Soumettre mes réponses
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
