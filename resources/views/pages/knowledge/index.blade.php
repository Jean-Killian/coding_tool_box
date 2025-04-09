<x-app-layout>
    <x-slot name="header">
        <h1 class="flex items-center gap-1 text-sm font-normal">
            <span class="text-gray-700">
                @extends('layouts.app')

                @section('content')
                    <h1 class="text-xl font-bold mb-4">QCM Laravel</h1>

                    <form action="{{ route('qcm.submit') }}" method="POST">
                    @csrf
                        @foreach($qcm as $index => $question)
                            <div class="mb-4">
                            <p class="font-semibold">{{ $index + 1 }}. {{ $question['question'] }}</p>
                            @foreach($question['options'] as $option)
                                    <label class="block">
                                        <input type="radio" name="answers[{{ $index }}]" value="{{ $option }}" required>
                                        {{ $option }}
                                    </label>
                                @endforeach
                            </div>
                        @endforeach
                    <button type="submit" class="btn btn-primary">Soumettre</button>
                    </form>
                @endsection
            </span>
        </h1>
    </x-slot>
</x-app-layout>

