<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class TestAPI extends Controller
{
    //
    public function getTestAPI()
    {
        $resultat=[
            'Developpeur'=>'Tamby Arimisa',
            'Base de donne'=>DB::connection()->getDatabaseName(),
        ]; 
        return response()->json($resultat);
    }
}
