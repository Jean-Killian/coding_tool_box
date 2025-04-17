<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-bold">Résultat du QCM</h1>
    </x-slot>

    <div class="p-6 space-y-4">
        <p class="text-xl font-semibold text-green-700">🎯 Score : {{ $score }} / {{ $total }}</p>

        @foreach ($quiz as $i => $question)
            <div class="border p-4 rounded bg-white shadow space-y-2">
                <p class="font-semibold">{{ $i + 1 }}. {{ $question['question'] }}</p>

                <ul class="space-y-2">
                    @foreach ($question['options'] as $option)
                        @php
                            $isCorrect = $option === $question['answer'];
                            $isUserWrong = isset($answers[$i]) && $answers[$i] !== $question['answer'] && $answers[$i] === $option;
                            $isUserSelected = isset($answers[$i]) && $answers[$i] === $option;
                        @endphp

                        <li class="px-4 py-2 rounded text-white font-medium flex items-center
                            @if ($isCorrect)
                                bg-green-600 border-2 border-green-800
                            @elseif ($isUserWrong)
                                bg-red-600 border-2 border-red-800
                            @else
                                bg-gray-200 text-black
                            @endif
                        ">
                            @if ($isCorrect)
                                ✅
                            @elseif ($isUserWrong)
                                ❌
                            @else
                                ◻️
                            @endif
                            <span class="ml-2">{{ $option }}</span>
                        </li>
                    @endforeach
                </ul>

                <p class="text-sm text-gray-600">
                    📝 Votre réponse : <strong>{{ $answers[$i] ?? 'Non répondu' }}</strong>
                </p>
            </div>
        @endforeach

        <a href="{{ route('knowledge.index') }}" class="inline-block mt-6 text-blue-600 underline">
            ⬅ Retour à l'accueil
        </a>
    </div>
</x-app-layout>

