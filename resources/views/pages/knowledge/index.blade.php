<x-app-layout>
    <x-slot name="header">
        <h1 class="text-lg font-bold">Résultat de l'IA (QCM Laravel)</h1>
    </x-slot>

    <div class="p-4">

        <form action="{{ route('knowledge.index') }}" method="GET" class="mb-6 space-y-4 bg-white p-6 rounded shadow max-w-xl mx-auto">
            <div>
                <label for="subject" class="block font-semibold mb-1">Sujet du QCM :</label>
                <input type="text" name="subject" id="subject" value="{{ old('subject', $subject ?? '') }}" required
                       class="w-full border p-2 rounded" placeholder="Ex : PHP, Laravel, JavaScript">
            </div>

            <div>
                <label for="count" class="block font-semibold mb-1">Nombre de questions :</label>
                <input type="number" name="count" id="count" value="{{ old('count', $count ?? 5) }}" min="1" max="20" required
                       class="w-full border p-2 rounded">
            </div>

            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                Générer le QCM
            </button>
        </form>

        @php
            $parsed = json_decode($iaResponse, true);
        @endphp

        @if ($errors->any())
            <div class="bg-red-100 text-red-800 p-4 rounded mb-4">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if(isset($iaResponse))
            <div class="max-w-4xl mx-auto bg-gray-100 p-6 rounded mt-6 shadow">
                <h2 class="text-lg font-semibold mb-4">
                    Résultat généré par Mistral :
                </h2>

                <div class="whitespace-pre-wrap font-mono text-sm bg-white p-4 rounded border mb-4">
                    {{ $iaResponse }}
                </div>

                @php
                    $parsed = json_decode($iaResponse, true);
                @endphp

                @if(is_array($parsed))
                    <h3 class="text-md font-bold mb-2">QCM structuré :</h3>
                    <ol class="space-y-6">
                        @foreach($parsed as $i => $q)
                            <li>
                                <p class="font-semibold">{{ $i + 1 }}. {{ $q['question'] }}</p>
                                <ul class="list-disc list-inside ml-4">
                                    @foreach($q['options'] as $option)
                                        <li>{{ $option }}</li>
                                    @endforeach
                                </ul>
                                <p class="text-green-600 text-sm mt-1">✅ Réponse : {{ $q['answer'] }}</p>
                            </li>
                        @endforeach
                    </ol>
                @else
                    <p class="text-red-600 font-semibold">⚠️ La réponse de l'IA n'est pas un JSON valide.</p>
                @endif
            </div>
        @endif
    </div>
</x-app-layout>


