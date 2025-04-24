<ul>
    @foreach($interns as $interns)
    <li>
        {{ $interns->name }} - {{ $interns->email }} - {{ $interns->age }} years 
    </li>
    @endforeach
</ul>