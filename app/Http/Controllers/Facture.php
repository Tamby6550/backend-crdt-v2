<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Facture extends Controller
{
    //Vef_examen dans registre est 2 et verf_fact = 0
    public function getNonFacture()
    {    
        $sql="SELECT to_char(sysdate,'MM/DD/YYYY')  as jourj, to_char(DATE_ARR,'DD/MM/YYYY') as date_arr,to_char(DATE_ARR,'MM/DD/YYYY') as date_arrive,NUMERO as numero,ID_PATIENT as id_patient,TYPE_PATIENT as type_pat,VERF_EXAMEN as verf_exam,
        NOM as nom,to_char(DATE_NAISS,'DD/MM/YYYY')  as date_naiss,TELEPHONE as telephone FROM CRDTPAT.LISTEREGISTRE 
        WHERE VERF_EXAMEN='2' AND VERF_FACT='0' order by LAST_UPDATE DESC";
        $req=DB::select($sql); 
        
        return response()->json($req);
    }
    public function getPatientExamenFacture($num_arriv,$date_arriv)
    {    
        $data1=array();
        $sql1="select sum(ex.QUANTITE*ex.MONTANT) as total from MIANDRALITINA.EXAMEN_DETAILS ex where ex.NUM_ARRIV='".$num_arriv."' AND ex.DATE_ARRIV=TO_DATE('".$date_arriv."','dd-mm-yyyy') ";
        $requette=DB::select($sql1);

        $sql="SELECT ex.*,to_char(ex.DATE_EXAMEN,'DD/MM/YYYY') as date_exam FROM MIANDRALITINA.EXAMEN_DETAILS ex WHERE NUM_ARRIV='".$num_arriv."' AND DATE_ARRIV=TO_DATE('".$date_arriv."','dd-mm-yyyy') order by LIB_EXAMEN DESC";
        $req=DB::select($sql); 
        foreach($requette as $row){
            $data1=$row;
        }
        foreach($data1 as $row){
            $data1=$row;
        }
        
        $resultat=[
            'total'=>$data1,
            'all'=>$req
        ]; 
        return response()->json($resultat);
    }

    //Affiche zavtr ilaina
    public function getPageFacture($num_arriv,$date_arriv)
    {
        $resultat=array();
        $data1=array();
        $sqlIdFacture="SELECT MIANDRALITINA.FORMAT_NUM(SYSDATE)  as num_facture,sysdate as datej,ls.TYPE_PATIENT as tarif FROM CRDTPAT.LISTEREGISTRE ls where ls.DATE_ARR=TO_DATE('".$date_arriv."','dd-mm-yyyy') and ls.NUMERO='".$num_arriv."'";
        $req1=DB::select($sqlIdFacture);
        foreach($req1 as $row){
            $data1=$row;
        }
        return response()->json($data1);
    }
    public function changmentTarif(Request $req)
    {
        $id_patient = $req->input("id_patient");

        $donneExam = $req->input("donne");
        $num_arriv = $req->input("num_arriv");
        $date_arriv = $req->input("date_arriv");
        $tarifNouveau = $req->input("tarif");
        $a=array();
        $verf=0;
        $data1=array();
        $nouveauMontant=0;

        $sqlUpdatePatient="UPDATE crdtpat.PATIENT SET TYPE_PATIENT=trim(?),LAST_UPDATE=sysdate WHERE ID_PATIENT=?";

        $sqlUpdate="UPDATE MIANDRALITINA.EXAMEN_DETAILS SET MONTANT=? WHERE NUM_ARRIV=? AND  DATE_ARRIV=TO_DATE(?,'dd-mm-yyyy') AND trim(upper(LIB_EXAMEN)) = trim(upper(?))";
        for ($i=0; $i < count($donneExam); $i++) { 
            $lib_examen = $donneExam[$i]['lib_examen'];
            $code_tarif = $donneExam[$i]['code_tarif'];
            $types=$donneExam[$i]['type'];
            //Maka ny montant tarif vao2
            $sql="SELECT MONTANT as montant FROM MIANDRALITINA.EXAMEN ex WHERE CODE_TARIF='".$code_tarif."' AND trim(upper(LIBELLE)) = trim(upper('".$lib_examen."'))  AND TARIF='".$tarifNouveau."' AND trim(upper(TYPES)) = trim(upper('".$types."'))";
            $req1=DB::select($sql);
            foreach($req1 as $row){
                $data1=$row;
            }
            foreach($data1 as $row){
                $data1=$row;
            }
            $nouveauMontant=$data1;
            
            $a[$i]=$nouveauMontant;
            // //Manova ny table examens details
            $donne=[$a[$i],$num_arriv,$date_arriv,$lib_examen];
            try {
                $req2=DB::update($sqlUpdate,$donne);
                $verf=1;
            } catch (\Throwable $th) {
                $verf=0;
                break;
            }
        }
        if ($verf==1) {
            $requette=DB::update($sqlUpdatePatient,[$tarifNouveau,$id_patient]);
            $resultat=[
                "etat"=>'success',
                "message"=>"Modification tarif éfféctuée avec succés ",
                'num_arriv'=>$num_arriv, 
                'date_arriv'=>$date_arriv, 
                'donneExam'=>$donneExam[0]['lib_examen'],
                'donneExam'=>count($donneExam)
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
