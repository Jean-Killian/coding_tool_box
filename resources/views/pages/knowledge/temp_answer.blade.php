<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-bold">Répondre au QCM : {{ $subject }}</h1>
    </x-slot>

    <form method="POST" action="{{ route('knowledge.temp.submit') }}" class="space-y-6 p-6">
        @csrf
        @foreach ($quiz as $i => $question)
            <div class="border p-4 rounded shadow">
                <p class="font-semibold">{{ $i + 1 }}. {{ $question['question'] }}</p>
                @foreach ($question['options'] as $option)
                    <label class="block">
                        <input type="radio" name="answers[{{ $i }}]" value="{{ $option }}" required>
                        {{ $option }}
                    </label>
                @endforeach
            </div>
        @endforeach

        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            Soumettre
        </button>
    </form>
</x-app-layout>
