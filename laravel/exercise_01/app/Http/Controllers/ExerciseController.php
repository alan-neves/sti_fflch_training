<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use League\Csv\Reader;

class ExerciseController extends Controller
{
    public function importCsv()
    {
    // Configure the CSV reader
    $csv = Reader::createFromPath(storage_path('app/exercise.csv'), 'r');
    $csv->setHeaderOffset(0); // Ignore the header

    // Clear the table before importing
    Exercise::truncate();

    // Import each row
    foreach ($csv as $line) {
        $exercise = new Exercise(); 
        $exercise->diet = $line['diet'];
        $exercise->pulse = $line['pulse'];
        $exercise->time = $line['time'];
        $exercise->kind = $line['kind'];
        $exercise->save();
        }

    return redirect('/exercises/stats'); 
    }

    public function stats() 
    { 
    // Calculation of statistics 
    $statistic = [ 
        'rest' => [ 
            'amount' => Exercise::where('kind', 'rest')->count(), 
            'average_pulse' => Exercise::where('kind', 'rest')->avg('pulse') 
        ], 
        'walking' => [ 
            'amount' => Exercise::where('kind', 'walking')->count(), 
            'average_pulse' => Exercise::where('kind', 'walking')->avg('pulse') 
        ], 
        'running' => [ 
            'amount' => Exercise::where('kind', 'running')->count(), 
            'average_pulse' => Exercise::where('kind', 'running')->avg('pulse') 
        ] 
    ]; 

    return view('exercises.stats', ['data' => $statistic]); 
    }
}
