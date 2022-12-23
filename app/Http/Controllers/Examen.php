<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Examen extends Controller
{
    public function getAllExamen()
    {
        $resultat=array();
        $data1=array();
        $sqlIdExam="SELECT nvl(max(ID_EXAMEN),0)+1 as nbenreg FROM crdtpat.EXAMEN ";
        $sqlExam="SELECT ID_EXAMEN,nvl(LIBELLE,' ') as LIB,CODE_TARIF,TYPES,MONTANT,TARIF  FROM crdtpat.EXAMEN order by ID_EXAMEN DESC";
        $req1=DB::select($sqlIdExam);
        $req2=DB::select($sqlExam); 
        foreach($req1 as $row){
            $data1=$row;
        }
        foreach($data1 as $row){
            $data1=$row;
        }
        
        $resultat=[
            'nbenreg'=>$data1,
            'allexamen'=>$req2
        ]; 
        return response()->json($resultat);
    }
   
    public function insertExamen(Request $req)
    {
        $resultat=array();
        $id_exam = $req->input("id_exam");
        $code_tarif = $req->input("code_tarif");
        $montant = $req->input("montant");
        $desc = $req->input("desc");
        $type = $req->input("type");
        $tar = $req->input("tarif");
        $login = $req->input("login");
        $donne=[$id_exam,$code_tarif,$desc,$type,$montant,$tar,$login];
        $sqlInsert="INSERT INTO crdtpat.EXAMEN (ID_EXAMEN,CODE_TARIF,LIBELLE,TYPES,MONTANT,TARIF,LAST_UPDATE, USER_UPDATE) values (?,trim(upper(?)),trim(upper(?)),trim(upper(?)),trim(?),trim(upper(?)),sysdate,?)";
        $requette=DB::insert($sqlInsert,$donne);

        if (!is_null($requette)) {
            $resultat=[
                "success"=>true,
                "message"=>"Enregistrement éfféctuée",
                'res'=>$requette 
            ];
           }else{
            $resultat=[
                "success"=>false, 
                "message"=>"Erreur sur l'enregistrement" 
            ];
           }
           return response()->json($resultat);
    }
    public function rechercheExamen(Request $req)
    {
        $sql="";
        $desc = $req->input("desc");
        $code_tarif = $req->input("code_tarif");
        $type = $req->input("type");
        $tarif = $req->input("tarif");
        $sql="SELECT ID_EXAMEN,nvl(LIBELLE,'') as LIB,CODE_TARIF,TYPES,MONTANT,TARIF  from crdtpat.EXAMEN WHERE 1=1";

        if ($desc != "") {
            $sql = $sql ." AND upper(LIBELLE) like upper('%".$desc."%') ";
        }
        if ($code_tarif != "") {
            $sql = $sql . " AND upper(code_tarif) like upper('%" . $code_tarif . "%') ";
        }
        if ($type!= "") {
            $sql = $sql . " AND types='" . $type . "' ";
        }
        if ($tarif!="") {
            $sql = $sql . " AND tarif='".$tarif."' ";
        }
        $sql = $sql ." ORDER BY LAST_UPDATE DESC";
        $requette=DB::select($sql);

        return response()->json($requette);
    }
    public function updateExamen(Request $req)
    {
        $resultat=array();
        $id_exam = $req->input("id_exam");
        $code_tarif = $req->input("code_tarif");
        $montant = $req->input("montant");
        $desc = $req->input("desc");
        $type = $req->input("type");
        $tar = $req->input("tarif");
        $login = $req->input("login");

        $donne=[$code_tarif,$desc,$montant,$type,$tar,$login,$id_exam];
        $sql="UPDATE crdtpat.EXAMEN SET CODE_TARIF=trim(upper(?)),LIBELLE=trim(upper(?)),MONTANT=upper(trim(?)),TYPES=trim(upper(?)),TARIF=trim(upper(?)),LAST_UPDATE=sysdate,USER_UPDATE=? WHERE ID_EXAMEN=?";
        
        $requette=DB::update($sql, $donne);
        if (!is_null($requette)) {
         $resultat=[
             "success"=>true,
             "message"=>"Modification éfféctuée",
             'res'=>$requette 
         ];
        }
        return response()->json($resultat);
    }
    public function deleteExamen($id_exam)
    {
        $sql="DELETE FROM crdtpat.EXAMEN WHERE ID_EXAMEN=?";

        $resultat=[];
        $requette=DB::delete($sql, [$id_exam]);
        if (!is_null($requette)) {
            $resultat=[
                "success"=>true,
                "message"=>"Suppression éfféctuée",
                'res'=>$requette 
            ];
        }
        return response()->json($resultat);
    }
    public function rechercheExamParTarif($tarif)
    {
        $sql="";
        //substr exemple : substr(L1,1,1)= L
        $sql="SELECT ID_EXAMEN,nvl(LIBELLE,'') as LIB,CODE_TARIF,TYPES,MONTANT,TARIF  from crdtpat.EXAMEN WHERE TARIF=substr(?,1,1) order by LAST_UPDATE DESC ";
        $requette=DB::select($sql,[$tarif]);

        return response()->json($requette);
    }
   
}
