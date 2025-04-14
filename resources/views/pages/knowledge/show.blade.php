<x-app-layout>
    <!-- Page header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">🧾 QCM : {{ $quiz->subject }}</h1>
    </x-slot>

    <!-- Main container -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow space-y-6">

        <!-- Quiz details -->
        <p><strong>Nombre de questions :</strong> {{ $quiz->question_count }}</p>
        <p><strong>Date de création :</strong> {{ $quiz->created_at->format('d/m/Y H:i') }}</p>

        <!-- Questions list -->
        <ol class="space-y-4">
            @foreach($quiz->questions as $i => $question)
                <li>
                    <!-- Question text -->
                    <p class="font-semibold">{{ $i + 1 }}. {{ $question['question'] }}</p>
                    <p class="text-sm text-gray-500 italic">Difficulté : {{ ucfirst($question['difficulty']) }}</p>

                    <!-- Options list -->
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
    </div>
</x-app-layout>

