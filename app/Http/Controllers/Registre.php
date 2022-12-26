<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Registre extends Controller
{
    public function getNumArriv()
    {
        $resultat=array();
        $data1=array();

        $datej=date('d/m/Y');
        $sqlRegistre="select crdtpat.FORMAT_NUM_REGISTR(sysdate) from dual";
        $req=DB::select($sqlRegistre); 
        foreach($req as $row){
            $data1=$row;
        }
        foreach($data1 as $row){
            $data1=$row;
        }
        $resultat=[
            'numarr'=>$data1,
            'datej'=>$datej,
        ];
        
        return response()->json($resultat);
    }

    public function insertRegistre(Request $req)
    {
        $resultat=array();
        $num_arriv = $req->input("num_arriv");
        $date_arriv = $req->input("date_arriv");
        $id_patient = $req->input("id_patient");
        $verf_exam = 0;
     
        $donne=[$num_arriv,$date_arriv,$id_patient,$verf_exam];
        $sqlInsert="INSERT INTO crdtpat.REGISTRE (NUM_ARRIV,DATE_ARRIV,ID_PATIENT,VERF_EXAM) values (?,TO_DATE(?,'dd-mm-yyyy'),?,?)";
        $requette=DB::insert($sqlInsert,$donne);

        if ($requette) {
            $resultat=[
                "etat"=>'success',
                "message"=>"Numéro de journal d'arrivée : ".$num_arriv,
                'num_arr'=>$num_arriv 
            ];
           }else{
            $resultat=[
                "success"=>false, 
                "message"=>"Erreur sur l'enregistrement" 
            ];
           }
           return response()->json($resultat);
    }
}
