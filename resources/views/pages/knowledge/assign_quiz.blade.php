<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-bold">🎯 Affecter un QCM à une cohorte</h1>
    </x-slot>

    <form method="POST" action="{{ route('knowledge.assign.quiz') }}" class="space-y-4 max-w-lg mx-auto mt-6 bg-white p-6 rounded shadow">
        @csrf

        <div>
            <label class="block font-semibold mb-1">Sélectionner un QCM</label>
            <select name="quiz_id" class="w-full border-gray-300 rounded">
                @foreach ($quizzes as $quiz)
                    <option value="{{ $quiz->id }}">{{ $quiz->subject }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Sélectionner une cohorte</label>
            <select name="cohort_id" class="w-full border-gray-300 rounded">
                @foreach ($cohorts as $cohort)
                    <option value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                @endforeach
            </select>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            ✅ Affecter
        </button>
    </form>
</x-app-layout>
