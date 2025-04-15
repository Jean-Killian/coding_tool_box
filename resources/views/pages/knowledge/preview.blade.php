<x-app-layout>
    <!-- Page header -->
    <x-slot name="header">
        <h1 class="text-lg font-bold">🧐 Aperçu du QCM généré</h1>
    </x-slot>

    <!-- Main container -->
    <div class="max-w-4xl mx-auto bg-white p-6 rounded shadow space-y-6">

        <!-- Quiz subject and number of questions -->
        <h2 class="text-md font-semibold">Sujet : {{ $subject }} | {{ $questionCount }} questions</h2>

        <!-- Display questions list -->
        <ol class="space-y-4">
            @if (is_array($qcm))
                @foreach($qcm as $i => $question)
                    <li>
                        <!-- Question text -->
                        <p class="font-semibold">{{ $i + 1 }}. {{ $question['question'] }}</p>
                        <p class="text-sm text-gray-500 italic">Difficulté : {{ ucfirst($question['difficulty']) }}</p>

                        <!-- Question options -->
                        <ul class="list-disc list-inside ml-4">
                            @foreach($question['options'] as $option)
                                <li>{{ $option }}</li>
                            @endforeach
                        </ul>

                        <!-- Correct answer -->
                        <p class="text-green-600 text-sm">✅ Réponse : {{ $question['answer'] }}</p>
                    </li>
                @endforeach
            @else
                <!-- Error if QCM is invalid -->
                <p class="text-red-600">❌ Erreur : QCM invalide ou mal formaté.</p>
            @endif
        </ol>

        <!-- Form to submit the quiz -->
        <form action="{{ route('knowledge.store') }}" method="POST" class="space-y-4">
            @csrf

            <!-- Hidden fields for quiz data -->
            <input type="hidden" name="subject" value="{{ $subject }}">
            <input type="hidden" name="question_count" value="{{ $questionCount }}">
            <input type="hidden" name="qcm" value="{{ $qcmRaw }}">

            <!-- Checkbox to publish quiz -->

            <label class="block">
                <input type="checkbox" name="publish" value="1" checked>
                <span class="ml-2">Publier ce QCM pour les étudiants</span>
            </label>

            <!-- Submit button -->
            <div>
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded">
                    ✅ Valider et enregistrer le QCM
                </button>
            </div>

            <!-- Action buttons: regenerate or cancel -->
            <div class="flex justify-between mt-6">
                <!-- Regenerate quiz -->
                <a href="{{ route('knowledge.generate') }}"
                   class="bg-yellow-500 hover:bg-yellow-600 text-white px-4 py-2 rounded">
                    🔁 Regénérer un nouveau QCM
                </a>

                <!-- Cancel and go back -->
                <a href="{{ route('knowledge.index') }}"
                   class="bg-gray-400 hover:bg-gray-500 text-white px-4 py-2 rounded">
                    ❌ Annuler / Ne pas enregistrer
                </a>
            </div>
        </form>
    </div>
</x-app-layout>

