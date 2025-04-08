<form action="{{ route('admin.quiz.create') }}" method="POST">
    @csrf
    <label for="languages">Langages :</label>
    <input type="text" name="languages" placeholder="ex: PHP, Python" required>

    <label for="question_count">Nombre de questions :</label>
    <input type="number" name="question_count" required>

    <button type="submit">Créer le QCM</button>
</form>

@if(isset($quiz))
    <h3>Voici votre quiz généré :</h3>
    <ul>
        @foreach ($quiz['questions'] as $question)
            <li>{{ $question['question'] }} <br> Réponses possibles : {{ implode(', ', $question['choices']) }}</li>
        @endforeach
    </ul>
@endif
