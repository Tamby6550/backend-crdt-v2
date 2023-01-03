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
        WHERE VERF_EXAMEN='0' order by LAST_UPDATE ASC";
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
        $donneExam = $req->input("donne");
        $sqlInsert="INSERT INTO MIANDRALITINA.EXAMEN_DETAILS(NUM_FACT,LIB_EXAMEN,CODE_TARIF,QUANTITE,MONTANT,DATE_EXAMEN,TYPE,NUM_ARRIV,DATE_ARRIV) 
        values(?,?,?,REPLACE(?,'.',','),?,sysdate,?,?,TO_DATE(?,'dd-mm-yyyy'))";
        $sql="UPDATE crdtpat.REGISTRE SET VERF_EXAM=1 ,LAST_UPDATE=sysdate WHERE NUM_ARRIV='".$num_arriv."' AND DATE_ARRIV=TO_DATE('".$date_arriv."','dd-mm-yyyy') ";
        
        for ($i=0; $i < count($donneExam); $i++) { 
            $lib_examen = $donneExam[$i]['lib_examen'];
            $code_tarif = $donneExam[$i]['code_tarif'];
            $quantite = $donneExam[$i]['quantite'];
            $montant = $donneExam[$i]['montant'];
            $type_examen = $donneExam[$i]['type_examen'];
            $donne=[$num_facture,$lib_examen,$code_tarif,$quantite,$montant,$type_examen,$num_arriv,$date_arriv];
            try {
                $requette=DB::insert($sqlInsert,$donne);
                $verf=1;
            } catch (\Throwable $th) {
                $verf=0;
                break;
            }
        }
        
        if ($verf==1) {
            $requette=DB::update($sql);
            $resultat=[
                "etat"=>'success',
                "message"=>"Enregistrement éfféctuée "
                // 'num_arriv'=>$num_arriv, 
                // 'date_arriv'=>$date_arriv, 
                // 'donneExam'=>$donneExam[0]['lib_examen'],
                // 'donneExam'=>count($donneExam)
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

    public function getExamenEff()//Vef_examen dans registre est 1
    {    
        $sql="SELECT to_char(sysdate,'MM/DD/YYYY')  as jourj, to_char(DATE_ARR,'DD/MM/YYYY') as date_arr,to_char(DATE_ARR,'MM/DD/YYYY') as date_arrive,NUMERO as numero,ID_PATIENT as id_patient,TYPE_PATIENT as type_pat,VERF_EXAMEN as verf_exam,
        NOM as nom,to_char(DATE_NAISS,'DD/MM/YYYY')  as date_naiss,TELEPHONE as telephone FROM CRDTPAT.LISTEREGISTRE 
        WHERE VERF_EXAMEN='1' order by LAST_UPDATE ASC";
        $req=DB::select($sql); 

        return response()->json($req);
    }

    public function getPatientExamenEff($num_arriv,$date_arriv)
    {    
        $sql="SELECT ex.*,to_char(ex.DATE_EXAMEN,'DD/MM/YYYY') as date_exam FROM MIANDRALITINA.EXAMEN_DETAILS ex WHERE NUM_ARRIV='".$num_arriv."' AND DATE_ARRIV=TO_DATE('".$date_arriv."','dd-mm-yyyy') order by LIB_EXAMEN DESC";
        $req=DB::select($sql); 

        return response()->json($req);
    }

    public function deleteExamenDetails($num_arriv,$date_arriv,$lib_examen)
    {
    
        //Ovaina / ny tiret rehetra raha misy
        $lib_examen = str_replace('-', '/', $lib_examen);
        //Supprssion dans examen details
        //Rehefa iray no examen natao, ka supprimena dia mivadika ho lasa tsis examen vita
        $data1=array();

        $sqlNbreExam="SELECT MIANDRALITINA.COUNT_EXAMEN_DETAILS('".$num_arriv."', TO_DATE('".$date_arriv."','dd-mm-yyyy')) from dual";
        $req1=DB::select($sqlNbreExam);
        
        foreach($req1 as $row){
            $data1=$row;
        }
        foreach($data1 as $row){
            $data1=$row;
        }
        
        if ($data1==1) {//Ovaina ny registre ho lasa verfexam=0 ,
            $sql2="UPDATE crdtpat.REGISTRE SET VERF_EXAM=0,LAST_UPDATE=sysdate  WHERE NUM_ARRIV='".$num_arriv."' AND DATE_ARRIV=TO_DATE('".$date_arriv."','dd-mm-yyyy')  ";
            $req2=DB::update($sql2);
        }

        $sql="DELETE FROM MIANDRALITINA.EXAMEN_DETAILS WHERE NUM_ARRIV='".$num_arriv."' AND DATE_ARRIV=TO_DATE('".$date_arriv."','dd-mm-yyyy') AND trim(upper(LIB_EXAMEN)) like trim(upper('%".$lib_examen."%'))";
        $resultat=[];
        $requette=DB::delete($sql);
        if (!is_null($requette)) {
            $resultat=[
                "etat"=>'success',
                "message"=>"Suppression éfféctuée",
                'nbrexamen'=>$data1, 
                'res'=>$sqlNbreExam, 
            ];
        }
        return response()->json($resultat);
    }

    public function updateExamenDetailsCR(Request $req)
    {
        $resultat=array();
        $num_arriv = $req->input("num_arriv");
        $date_arriv = $req->input("date_arriv");
        $cr_name = $req->input("cr_name");
       
        $donne=[$cr_name,$num_arriv,$date_arriv,];
        $sql="UPDATE MIANDRALITINA.EXAMEN_DETAILS SET CR_NAME=? WHERE NUM_ARRIV=? AND  DATE_ARRIV=TO_DATE(?,'dd-mm-yyyy') ";
        
        $requette=DB::update($sql, $donne);
        if (!is_null($requette)) {
         $resultat=[
            "etat"=>'success',
             "message"=>"Modification éfféctuée",
             'res'=>$requette 
         ];
        }
        return response()->json($resultat);
    }
}
