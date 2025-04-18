<x-app-layout>
    {{-- Page header --}}
    <x-slot name="header">
        <h1 class="text-lg font-bold">🎯 Assign a Quiz to a Cohort</h1>
    </x-slot>

    {{-- Quiz assignment form --}}
    <form method="POST" action="{{ route('knowledge.assign.quiz') }}"
          class="space-y-4 max-w-lg mx-auto mt-6 bg-white p-6 rounded shadow">
        @csrf

        {{-- Quiz selection dropdown --}}
        <div>
            <label class="block font-semibold mb-1">Select a Quiz</label>
            <select name="quiz_id" class="w-full border-gray-300 rounded">
                @foreach ($quizzes as $quiz)
                    <option value="{{ $quiz->id }}"
                        {{ (isset($selectedQuizId) && $selectedQuizId == $quiz->id) ? 'selected' : '' }}>
                        {{ $quiz->subject }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Cohort selection dropdown --}}
        <div>
            <label class="block font-semibold mb-1">Select a Cohort</label>
            <select name="cohort_id" class="w-full border-gray-300 rounded">
                @foreach ($cohorts as $cohort)
                    <option value="{{ $cohort->id }}">{{ $cohort->name }}</option>
                @endforeach
            </select>
        </div>

        {{-- Submit button to assign quiz --}}
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">
            ✅ Affecter
        </button>
    </form>
</x-app-layout>

