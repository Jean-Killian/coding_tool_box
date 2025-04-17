{{--<x-app-layout>
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

        <!-- Submit button -->
        @if(auth()->user()->isTeacher())
            <hr>
            <form method="POST" action="{{ route('knowledge.assign.quiz') }}" class="space-y-2">
                @csrf

                <input type="hidden" name="quiz_id" value="{{ $quiz->id }}">

                <label for="cohort_id" class="block font-medium">Affecter à une cohorte :</label>
                <select name="cohort_id" id="cohort_id" class="border rounded p-2 w-full" required>
                    <option value="">-- Sélectionner une cohorte --</option>
                    @foreach($cohorts as $cohort)
                        <option value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    📢 Publier ce QCM
                </button>
            </form>
        @endif

        {{-- ✅ Message de confirmation si succès
        @if(session('success'))
            <div class="mt-4 p-3 bg-green-100 text-green-800 rounded">
                {{ session('success') }}
            </div>
        @endif
    </div>
</x-app-layout>--}}
