<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;

class ExamenDuJour extends Controller
{
    public function getExamenNonEff()//Vef_examen dans registre est 0
    {    
        $sql="SELECT to_char(sysdate,'MM/DD/YYYY')  as jourj, to_char(DATE_ARR,'DD/MM/YYYY') as date_arr,to_char(DATE_ARR,'MM/DD/YYYY') as date_arrive,NUMERO as numero,ID_PATIENT as id_patient,TYPE_PATIENT as type_pat,VERF_EXAMEN as verf_exam,
        NOM as nom,to_char(DATE_NAISS,'DD/MM/YYYY')  as date_naiss,TELEPHONE as telephone FROM CRDTPAT.LISTEREGISTRE 
        WHERE VERF_EXAMEN='0' order by NUMERO DESC";
        $req=DB::select($sql); 

        return response()->json($req);
    }
    public function insertExamenJour(Request $req)
    {
        $resultat=array();
        $nbinput=$req->input('nbinput');
        $verf=0;
        $num_facture = "-";
        $num_arriv = $req->input("num_arriv");
        $date_arriv = $req->input("date_arriv");
        $sqlInsert="INSERT INTO MIANDRALITINA.EXAMEN_DETAILS(NUM_FACT,LIB_EXAMEN,CODE_TARIF,QUANTITE,MONTANT,DATE_EXAMEN,TYPE,NUM_ARRIV,DATE_ARRIV) 
        values(?,?,?,REPLACE(?,'.',','),?,sysdate,?,?,TO_DATE(?,'dd-mm-yyyy'))";
        for ($i=1; $i <= $nbinput; $i++) { 
            $lib_examen = $req->input("lib_examen".$i);
            $code_tarif = $req->input("code_tarif".$i);
            $quantite = $req->input("quantite".$i);
            $montant = $req->input("montant".$i);
            $type_examen = $req->input("type_examen".$i);
            $donne=[$num_facture,$lib_examen,$code_tarif,$quantite,$montant,$type_examen,$num_arriv,$date_arriv];
            try {
                $requette=DB::insert($sqlInsert,$donne);
                $verf=$i;
            } catch (\Throwable $th) {
                $verf=$i-1;
            }
        }
       

        if ($verf==$nbinput) {
            $resultat=[
                "etat"=>'success',
                "message"=>"Enregistrement éfféctuée ",
                'res'=>$sqlInsert 
            ];
        }
        else{
            $resultat=[
                "etat"=>'error',
                "message"=>"Erreur sur l'enregistrement , verifier la connexion de la base de donne" 
            ];
        }
        return response()->json($resultat);
    }

}
