<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Facture extends Controller
{
    public function getNonFacture()//Vef_examen dans registre est 2 et verf_fact = 0
    {    
        $sql="SELECT to_char(sysdate,'MM/DD/YYYY')  as jourj, to_char(DATE_ARR,'DD/MM/YYYY') as date_arr,to_char(DATE_ARR,'MM/DD/YYYY') as date_arrive,NUMERO as numero,ID_PATIENT as id_patient,TYPE_PATIENT as type_pat,VERF_EXAMEN as verf_exam,
        NOM as nom,to_char(DATE_NAISS,'DD/MM/YYYY')  as date_naiss,TELEPHONE as telephone FROM CRDTPAT.LISTEREGISTRE 
        WHERE VERF_EXAMEN='2' AND VERF_FACT='0' order by LAST_UPDATE DESC";
        $req=DB::select($sql); 
        
        return response()->json($req);
    }
}
