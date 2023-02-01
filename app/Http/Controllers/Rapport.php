<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class Rapport extends Controller
{
    //Facture du jour
    public function getMtFacturejour($date_facture)
    {
        $sql1="SELECT NVL(MIN(substr(NUM_FACT,7,4)),0) as starts ,NVL(MAX(substr(NUM_FACT,7,4)),0) as ends,NVL(count(*),0) as counts,
        trim(NVL(to_char(sum(MONTANT_NET),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. '''),0)) as montant,
        trim(NVL(to_char(sum(MONTANT_PATIENT_REGLE+MONTANT_PEC_REGLE),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. '''),0)) as montant_rglmt 
        FROM MIANDRALITINA.BILLING1 WHERE to_char(DATE_EXAMEN,'DD-MM-YYYY')='".$date_facture."'";
        $req1=DB::select($sql1); 

        $data1=array();
        foreach($req1 as $row){
            $data1=$row;
        }

        $starts=$data1->starts;
        $ends=$data1->ends;
        $counts=$data1->counts;
        $montant=$data1->montant;
        $montant_rglmt=$data1->montant_rglmt;

        $resultat=[
            'starts'=>$starts,
            'ends'=>$ends,
            'counts'=>$counts,
            'montant'=>$montant,
            'montant_rglmt'=>$montant_rglmt,
        ]; 
        return response()->json($resultat);
    }
    public function getFactureJour($starts,$ends,$date_facture)
    {    
        $sql2="SELECT to_char(DATE_EXAMEN,'DD/MM/YYYY') as DATE_EXAMEN,NUM_FACT,substr(CLIENT,1,25) as CLIENT,substr(PATIENT,1,29) as PATIENT,
        trim(to_char(MONTANT_NET,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) MONTANT,substr(REGLEMNT,1,1) as REGLEMNT,
        trim(to_char(MONTANT_PATIENT_REGLE+MONTANT_PEC_REGLE,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) MONTANT_REGL,
        substr(TYPE_FACTURE,1,1) as TYPE_FACTURE ,MIANDRALITINA.VIEW_ECHO(NUM_FACT) as ECHO,MIANDRALITINA.VIEW_MAMMO(NUM_FACT) as MAMO,
        MIANDRALITINA.VIEW_PANO(NUM_FACT) as PANNO,MIANDRALITINA.VIEW_ECG(NUM_FACT) as ECG,MIANDRALITINA.VIEW_AUTRES(NUM_FACT) as PRODUIT,
        MIANDRALITINA.VIEW_RADIO(NUM_FACT) as RADIO,MIANDRALITINA.VIEW_SCAN(NUM_FACT) as SCAN,OBSERVATION 
        FROM MIANDRALITINA.BILLING1 WHERE to_char(DATE_EXAMEN,'DD-MM-YYYY')='".$date_facture."' 
        and ( to_number(substr(NUM_FACT,7,4))>='".$starts."' and to_number(substr(NUM_FACT,7,4))<='".$ends."') ORDER BY NUM_FACT ASC";
        $req2=DB::select($sql2); 

        return response()->json(['Data'=>$req2]);
    }
    
    // -------------------------------------------Recette du jour---------------------------------------//
    public function getMtRecettejour($date_facture)
    {
        //Espèces
        $sql1ESP="SELECT to_char(NVL(sum(MONTANT),0),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''') as MONTANT_ESP 
        FROM MIANDRALITINA.REGLEMENT_DETAILS A,MIANDRALITINA.FACTURE B WHERE A.NUM_FACT=B.NUM_FACT
        AND TYPE_FACTURE='0' AND A.REGLEMENT_ID=1 AND to_char(DATE_REGLEMENT,'DD-MM-YYYY')='".$date_facture."'";

        //Chèques
        $sql1CH="SELECT to_char(NVL(sum(MONTANT),0),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''') as MONTANT_CHQ 
        FROM MIANDRALITINA.REGLEMENT_DETAILS A,MIANDRALITINA.FACTURE B WHERE A.NUM_FACT=B.NUM_FACT
        AND TYPE_FACTURE='0' AND A.REGLEMENT_ID=2 AND to_char(DATE_REGLEMENT,'DD-MM-YYYY')='".$date_facture."'";

        //Montant
        $sql1Mt="SELECT min(ID) as starts,max(ID) as ends,count(id) as counts,
        NVL(to_char(sum(MONTANT),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. '''),0) as montant
        FROM (SELECT ROWNUM as ID,to_char(DATE_REGLEMENT,'DD/MM/YYYY') as DATE_REGLMT,A.NUM_FACT as NUM_FACT,PATIENT,MIANDRALITINA.VIEW_CLIENT(CODE_CLIENT) as CLIENT,
        MIANDRALITINA.VIEW_REGLEMENT(A.REGLEMENT_ID) as REGLEMNT,MONTANT 
        FROM MIANDRALITINA.REGLEMENT_DETAILS A,MIANDRALITINA.FACTURE B
        WHERE A.NUM_FACT=B.NUM_FACT AND TYPE_FACTURE='0' AND A.REGLEMENT_ID in ('1','2') 
        AND to_char(DATE_REGLEMENT,'DD-MM-YYYY')='".$date_facture."'   ORDER BY A.NUM_FACT ASC  )";

        $req1=DB::select($sql1ESP); 
        $req2=DB::select($sql1CH); 
        $req3=DB::select($sql1Mt); 

        $data1=array();
        $data2=array();
        $data3=array();
        foreach($req1 as $row){
            $data1=$row;
        }
        foreach($req2 as $row){
            $data2=$row;
        }
        foreach($req3 as $row){
            $data3=$row;
        }

        $montant_esp=$data1->montant_esp;
        $montant_chq=$data2->montant_chq;

        $starts=$data3->starts;
        $ends=$data3->ends;
        $counts=$data3->counts;
        $montant=$data3->montant;

        $resultat=[
            'montant_chq'=>trim($montant_esp),
            'montant_esp'=>trim($montant_chq),
            'starts'=>trim($starts),
            'ends'=>trim($ends),
            'counts'=>trim($counts),
            'montant'=>trim($montant)
        ]; 

        return response()->json($resultat);
    }
    public function getRecetteJour($starts,$ends,$date_facture)
    {    
        $sql2="SELECT ID,DATE_REGLMT,NUM_FACT,PATIENT,CLIENT,REGLEMNT,trim(to_char(MONTANT,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT FROM 
        ( SELECT ROWNUM as ID,to_char(DATE_REGLEMENT,'DD/MM/YYYY') as DATE_REGLMT,A.NUM_FACT as NUM_FACT,
        PATIENT,MIANDRALITINA.VIEW_CLIENT(CODE_CLIENT) as CLIENT,MIANDRALITINA.VIEW_REGLEMENT(A.REGLEMENT_ID) as REGLEMNT,MONTANT 
        FROM MIANDRALITINA.REGLEMENT_DETAILS A,MIANDRALITINA.FACTURE B WHERE 
        A.NUM_FACT=B.NUM_FACT AND TYPE_FACTURE='0' AND A.REGLEMENT_ID in ('1','2') AND to_char(DATE_REGLEMENT,'DD-MM-YYYY')='".$date_facture."' ) 
        WHERE ID>='".$starts."' and ID <='".$ends."' ORDER BY NUM_FACT ASC";
        $req2=DB::select($sql2); 

        return response()->json(['Data'=>$req2]);
    }



     // -------------------------------------------Virement du jour---------------------------------------//
     public function getMtVirementjour($date_facture)
    {
        $sql1="SELECT min(ID) as starts,max(ID) as ends,count(id) as counts,
        trim(NVL(to_char(sum(MONTANT),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. '''),0)) as montant
        FROM (SELECT ROWNUM as ID,to_char(DATE_REGLEMENT,'DD/MM/YYYY') as DATE_REGLMT,A.NUM_FACT as NUM_FACT,PATIENT,MIANDRALITINA.VIEW_CLIENT(CODE_CLIENT) as CLIENT,
        MIANDRALITINA.VIEW_REGLEMENT(A.REGLEMENT_ID) as REGLEMNT,MONTANT FROM MIANDRALITINA.REGLEMENT_DETAILS A,MIANDRALITINA.FACTURE B 
        WHERE A.NUM_FACT=B.NUM_FACT AND TYPE_FACTURE='0' AND A.REGLEMENT_ID='3' AND to_char(DATE_REGLEMENT,'DD-MM-YYYY')='".$date_facture."'   ORDER BY A.NUM_FACT ASC)";
        $req1=DB::select($sql1); 

        $data1=array();
        foreach($req1 as $row){
            $data1=$row;
        }

        $starts=$data1->starts;
        $ends=$data1->ends;
        $counts=$data1->counts;
        $montant=$data1->montant;

        $resultat=[
            'starts'=>$starts,
            'ends'=>$ends,
            'counts'=>$counts,
            'montant'=>$montant,
        ]; 
        return response()->json($resultat);
    }
    public function getVirementJour($starts,$ends,$date_facture)
    {    
        $sql2="SELECT ID,DATE_REGLMT,NUM_FACT,PATIENT,CLIENT,REGLEMNT,to_char(MONTANT,'999G999G999G999G999') as MONTANT FROM 
        ( SELECT ROWNUM as ID,to_char(DATE_REGLEMENT,'DD/MM/YYYY') as DATE_REGLMT,A.NUM_FACT as NUM_FACT,
        PATIENT,MIANDRALITINA.VIEW_CLIENT(CODE_CLIENT) as CLIENT,MIANDRALITINA.VIEW_REGLEMENT(A.REGLEMENT_ID) as REGLEMNT,MONTANT FROM MIANDRALITINA.REGLEMENT_DETAILS A,MIANDRALITINA.FACTURE B WHERE 
        A.NUM_FACT=B.NUM_FACT AND TYPE_FACTURE='0' AND A.REGLEMENT_ID='3' AND to_char(DATE_REGLEMENT,'DD-MM-YYYY')='".$date_facture."' ) 
        WHERE ID>='".$starts."' and ID <='".$ends."' ORDER BY NUM_FACT ASC";
        $req2=DB::select($sql2); 

        return response()->json(['Data'=>$req2]);
    }
    
    
    // -------------------------------------------Statistique examen---------------------------------------//
    public function getStatExamen(Request $req)
    {
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");

        $sql1="SELECT sum(NOMBRE) NOMBRE,sum(MONTANT) as TOTAL,trim(to_char(sum(MONTANT),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) MONTANT FROM 
        (SELECT TYPE ,count(*) as NOMBRE ,sum(MONTANT_NET) as MONTANT FROM MIANDRALITINA.EXAMEN_STAT WHERE REJET<>'1' 
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') GROUP BY TYPE)";
        $req1=DB::select($sql1); 

        $data1=array();
        foreach($req1 as $row){
            $data1=$row;
        }

        $nombre=$data1->nombre;
        $total=$data1->total;
        $montant=$data1->montant;

        $sql="SELECT TYPE ,sum(MONTANT_NET) as MONT,count(*) as NOMBRE,trim(to_char(sum(MONTANT_NET),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT 
        FROM MIANDRALITINA.EXAMEN_STAT WHERE REJET<>'1'   
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') GROUP BY TYPE ORDER BY TYPE DESC";
        $req=DB::select($sql); 

        $resultat=[
            'nombre'=>$nombre,
            'total'=>$total,
            'montant'=>$montant,
            'data'=>$req,
        ]; 
        return response()->json($resultat);
    }


    // -------------------------------------------Statistique Client---------------------------------------//
    public function getMtClientStat(Request $req)
    {
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");
        $code_client=$req->input("code_client");

        $sql1="SELECT sum(QUANTITE) as QUANTITE,trim(to_char(sum(QUANTITE*MONTANT),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT 
        FROM MIANDRALITINA.EXAMEN_DETAILS A,MIANDRALITINA.FACTURE B  
        WHERE A.NUM_FACT=B.NUM_FACT and REJET<>'1' and TYPE<>'AUTRES' and CODE_CLIENT='".$code_client."' 
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy')";
        $req1=DB::select($sql1); 

        $data1=array();
        foreach($req1 as $row){
            $data1=$row;
        }

        $quantite=$data1->quantite;
        $montant=$data1->montant;

        $sql="SELECT  min(ID) as starts,max(ID) as ends,count(id) as counts FROM(SELECT ROWNUM as ID,NUM_FACT,LIB_EXAMEN,QUANTITE,PU,MONTANT,DATY FROM 
        ( SELECT A.NUM_FACT as NUM_FACT, LIB_EXAMEN, QUANTITE, MONTANT as PU, QUANTITE*MONTANT as MONTANT ,to_char(DATE_EXAMEN,'DD/MM/YYYY') as DATY 
        FROM MIANDRALITINA.EXAMEN_DETAILS A,MIANDRALITINA.FACTURE B WHERE A.NUM_FACT=B.NUM_FACT and REJET<>'1' and TYPE<>'AUTRES' and CODE_CLIENT='".$code_client."' 
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') ORDER BY DATE_EXAMEN))";
        $req=DB::select($sql); 

        $data2=array();
        foreach($req as $row){
            $data2=$row;
        }

        $starts=$data2->starts;
        $ends=$data2->ends;
        $counts=$data2->counts;
        
        $resultat=[
            'quantite'=>$quantite,
            'montant'=>$montant,
            'starts'=>$starts,
            'ends'=>$ends,
            'counts'=>$counts
        ]; 
        return response()->json($resultat);
    }
    public function getClientStat(Request $req)
    {    
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");
        $code_client=$req->input("code_client");
        $starts=$req->input("starts");
        $ends=$req->input("ends");

        $sql2="SELECT ID,NUM_FACT,LIB_EXAMEN,PATIENT,QUANTITE,
        trim(to_char(PU,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as PU,
        trim(to_char(MONTANT,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT,
        DATY FROM(SELECT ROWNUM as ID,NUM_FACT,LIB_EXAMEN,PATIENT,QUANTITE,PU,MONTANT,DATY FROM 
        (SELECT A.NUM_FACT as NUM_FACT, LIB_EXAMEN,PATIENT,QUANTITE, MONTANT as PU, QUANTITE*MONTANT as MONTANT ,to_char(DATE_EXAMEN,'DD/MM/YYYY') as DATY 
        FROM MIANDRALITINA.EXAMEN_DETAILS A,MIANDRALITINA.FACTURE B WHERE A.NUM_FACT=B.NUM_FACT and REJET<>'1'
        and TYPE<>'AUTRES' and CODE_CLIENT='".$code_client."' and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy')
        ORDER BY DATE_EXAMEN)) where ID>='".$starts."' and ID<='".$ends."'";
        $req2=DB::select($sql2); 
    
        return response()->json(['Data'=>$req2]);
    }


      // -------------------------------------------Statistique detaille examen---------------------------------------//
      public function getStatDetailleExamen(Request $req)
      {
          $date_deb=$req->input("date_deb");
          $date_fin=$req->input("date_fin");
  
          $sql1="SELECT min(ID) as starts,max(ID) as ends,count(id) as counts FROM( 
            SELECT ROWNUM as ID,EXAMEN,COUNT,MONTANT 
            FROM (SELECT LIB_EXAMEN as EXAMEN,sum(QUANTITE) as COUNT,SUM(MONTANT_NET) as MONTANT 
            FROM MIANDRALITINA.EXAMEN_STAT where REJET<>'1' and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') 
            and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') GROUP BY LIB_EXAMEN ORDER BY LIB_EXAMEN ASC))";
          $req1=DB::select($sql1); 
  
          $data1=array();
          foreach($req1 as $row){
              $data1=$row;
          }
  
          $starts=$data1->starts;
          $ends=$data1->ends;
          $counts=$data1->counts;
  
          $sql="SELECT ID,EXAMEN,COUNT,MONTANT FROM 
          (SELECT ROWNUM as ID,EXAMEN,COUNT,trim(to_char(MONTANT,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT FROM 
          (SELECT LIB_EXAMEN as EXAMEN,sum(QUANTITE) as COUNT,SUM(QUANTITE*MONTANT) as MONTANT 
          FROM MIANDRALITINA.EXAMEN_DETAILS WHERE trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') 
          and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') 
          and REJET<>'1' GROUP BY LIB_EXAMEN ORDER BY LIB_EXAMEN ASC))
          where ID>='".$starts."' and ID<='".$ends."'";
          $req=DB::select($sql); 
  
          $resultat=[
              'starts'=>$starts,
              'ends'=>$ends,
              'counts'=>$counts,
              'data'=>$req,
          ]; 
          return response()->json($resultat);
      }

    // -------------------------------------------Statistique Prescripteur---------------------------------------//
    public function getMtStatPrescripteur(Request $req)
    {
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");
        $code_presc=$req->input("code_presc");

        $sql1="SELECT sum(QUANTITE) as QUANTITE,trim(to_char(sum(QUANTITE*MONTANT),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT
        FROM MIANDRALITINA.EXAMEN_DETAILS A,MIANDRALITINA.FACTURE B  WHERE A.NUM_FACT=B.NUM_FACT and TYPE<>'PRODUIT' and CODE_PRESC='".$code_presc."' 
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy')";
        $req1=DB::select($sql1); 

        $data1=array();
        foreach($req1 as $row){
            $data1=$row;
        }

        $quantite=$data1->quantite;
        $montant=$data1->montant;

        $sql="SELECT min(ID) as starts,max(ID) as ends,count(id) as counts FROM(SELECT ROWNUM as ID,NUM_FACT,LIB_EXAMEN,QUANTITE,PU,MONTANT,DATY FROM 
        ( SELECT A.NUM_FACT as NUM_FACT, LIB_EXAMEN, QUANTITE, MONTANT as PU, QUANTITE*MONTANT as MONTANT ,to_char(DATE_EXAMEN,'DD/MM/YYYY') as DATY 
        FROM MIANDRALITINA.EXAMEN_DETAILS A,MIANDRALITINA.FACTURE B WHERE A.NUM_FACT=B.NUM_FACT and TYPE<>'PRODUIT' and CODE_PRESC='".$code_presc."' 
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') ORDER BY DATE_EXAMEN))";
        $req=DB::select($sql); 

        $data2=array();
        foreach($req as $row){
            $data2=$row;
        }

        $starts=$data2->starts;
        $ends=$data2->ends;
        $counts=$data2->counts;
        
        $resultat=[
            'quantite'=>$quantite,
            'montant'=>$montant,
            'starts'=>$starts,
            'ends'=>$ends,
            'counts'=>$counts
        ]; 
        return response()->json($resultat);
    }
    public function getStatPrescripteur(Request $req)
    {
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");
        $starts=$req->input("starts");
        $ends=$req->input("ends");
        $code_presc=$req->input("code_presc");

        $sql1="SELECT ID,NUM_FACT,LIB_EXAMEN,PATIENT,QUANTITE,
        trim(to_char(PU,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as PU,
        trim(to_char(MONTANT,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT,
        DATY FROM(SELECT ROWNUM as ID,NUM_FACT,LIB_EXAMEN,PATIENT,QUANTITE,PU,MONTANT,DATY FROM 
        (SELECT A.NUM_FACT as NUM_FACT, LIB_EXAMEN,PATIENT,QUANTITE, MONTANT as PU, QUANTITE*MONTANT as MONTANT ,to_char(DATE_EXAMEN,'DD/MM/YYYY') as DATY
        FROM MIANDRALITINA.EXAMEN_DETAILS A,MIANDRALITINA.FACTURE B WHERE A.NUM_FACT=B.NUM_FACT and TYPE<>'PRODUIT' 
        and CODE_PRESC='".$code_presc."' and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') 
        ORDER BY DATE_EXAMEN)) where ID>='".$starts."' and ID<='".$ends."'";
        $req1=DB::select($sql1); 

        return response()->json(['Data'=>$req1]);
    }
    
    

     // -------------------------------------------Statistique Catégorie---------------------------------------//
     public function getStatCategorie(Request $req)
     {
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");

        $sql1="SELECT sum(NOMBRE) NOMBRE,sum(MONTANT) as TOTAL,
        trim(to_char(sum(MONTANT),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) MONTANT 
        FROM ( SELECT TYPE_CLIENT ,count(*) as NOMBRE ,sum(MONTANT_NET) as MONTANT FROM MIANDRALITINA.EXAMEN_STAT WHERE REJET<>'1' 
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') GROUP BY TYPE_CLIENT)";
        $req1=DB::select($sql1); 

        $data1=array();
        foreach($req1 as $row){
            $data1=$row;
        }

        $nombre=$data1->nombre;
        $total=$data1->total;
        $montant=$data1->montant;

        $sql="SELECT TYPE_CLIENT ,sum(MONTANT_NET) as MONT,count(*) as NOMBRE,
        trim(to_char(sum(MONTANT_NET),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT 
        FROM MIANDRALITINA.EXAMEN_STAT WHERE REJET<>'1' and 
        trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') GROUP BY TYPE_CLIENT";
        $req=DB::select($sql); 

        $resultat=[
            'nombre'=>$nombre,
            'total'=>$total,
            'montant'=>$montant,
            'data'=>$req,
        ]; 
        return response()->json($resultat);
     }

     // -------------------------------------------Statistique Cumul chiffre d'affaire---------------------------------------//
     public function getCumulChiffre(Request $req)
     {
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");

        $sql1="SELECT sum(NOMBRE) NOMBRE,sum(MONTANT) as TOTAL,trim(to_char(sum(MONTANT),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) MONTANT 
        FROM ( SELECT TYPE ,count(*) as NOMBRE ,sum(MONTANT_NET) as MONTANT 
        FROM MIANDRALITINA.EXAMEN_STAT WHERE REJET<>'1'  and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') GROUP BY TYPE)";
        $req1=DB::select($sql1); 

        $data1=array();
        foreach($req1 as $row){
            $data1=$row;
        }

        $nombre=$data1->nombre;
        $total=$data1->total;
        $montant=$data1->montant;

        $sql="SELECT TYPE ,sum(MONTANT_NET) as MONT,count(*) as NOMBRE,
        trim(to_char(sum(MONTANT_NET),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as MONTANT 
        FROM MIANDRALITINA.EXAMEN_STAT WHERE REJET<>'1'  
        and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') GROUP BY TYPE";
        $req=DB::select($sql); 

        $resultat=[
            'nombre'=>$nombre,
            'total'=>$total,
            'montant'=>$montant,
            'data'=>$req,
        ]; 
        return response()->json($resultat);
     }



    // -------------------------------------------Releve facture---------------------------------------//
     public function getMtReleveFact(Request $req)
     {
         $date_deb=$req->input("date_deb");
         $date_fin=$req->input("date_fin");
         $code_client=$req->input("code_client");
 
         $sql1="SELECT trim(to_char(sum(MONTANT_PEC),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. '''))  as montant_pec,
         trim(to_char(sum(MONTANT_PEC_REGLE),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as montant_pec_regle,
         trim(to_char(sum(RESTE_PEC),'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as reste_pec_regle 
         FROM MIANDRALITINA.BILLING1 WHERE TYPE_FACTURE<>'Oui' and CODE_CLI='".$code_client."' and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy')";
         $req1=DB::select($sql1); 
 
         $data1=array();
         foreach($req1 as $row){
             $data1=$row;
         }
 
         $montant_pec=$data1->montant_pec;
         $montant_pec_regle=$data1->montant_pec_regle;
         $reste_pec_regle=$data1->reste_pec_regle;
 
         $sql="SELECT min(ID) as starts,max(ID) as ends,count(id) as counts 
         FROM (SELECT  ROWNUM AS ID,NUM_FACT,DATE_EXAMEN,MONTANT_PEC,MONTANT_PEC_REGLE,RESTE_PEC,PATIENT FROM MIANDRALITINA.BILLING1 
         WHERE TYPE_FACTURE<>'Oui' and CODE_CLI='".$code_client."' and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') 
         ORDER BY trunc(DATE_EXAMEN) ASC)";
         $req=DB::select($sql); 
 
         $data2=array();
         foreach($req as $row){
             $data2=$row;
         }
 
         $starts=$data2->starts;
         $ends=$data2->ends;
         $counts=$data2->counts;
         
         $resultat=[
             'montant_pec'=>$montant_pec,
             'montant_pec_regle'=>$montant_pec_regle,
             'reste_pec_regle'=>$reste_pec_regle,
             'starts'=>$starts,
             'ends'=>$ends,
             'counts'=>$counts
         ]; 
         return response()->json($resultat);
     }
     public function getRelevefacture(Request $req)
     {
        $date_deb=$req->input("date_deb");
        $date_fin=$req->input("date_fin");
        $starts=$req->input("starts");
        $ends=$req->input("ends");
        $code_client=$req->input("code_client");
     
        $sql1="SELECT NUM_FACT,to_char(DATE_EXAMEN,'DD/MM/YYYY') as DATE_EXAMEN,
        trim(to_char(MONTANT_PEC,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as pec,
        trim(to_char(MONTANT_PEC_REGLE,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as pec_regle,
        trim(to_char(RESTE_PEC,'999G999G999G999G999','NLS_NUMERIC_CHARACTERS=''. ''')) as reste_pec,PATIENT 
        FROM (SELECT ROWNUM AS ID,NUM_FACT, DATE_EXAMEN,MONTANT_PEC,MONTANT_PEC_REGLE,RESTE_PEC,PATIENT 
        FROM(SELECT  NUM_FACT,trunc(DATE_EXAMEN) as DATE_EXAMEN,MONTANT_PEC,MONTANT_PEC_REGLE,RESTE_PEC,PATIENT 
        FROM MIANDRALITINA.BILLING1 WHERE TYPE_FACTURE<>'Oui' 
        and CODE_CLI='".$code_client."' and trunc(DATE_EXAMEN)>=to_date('".$date_deb."','dd/mm/yyyy') 
        and trunc(DATE_EXAMEN)<=to_date('".$date_fin."','dd/mm/yyyy') ORDER BY DATE_EXAMEN,substr(NUM_FACT,7,4) ASC)) where ID>='".$starts."' and ID<='".$ends."'";
        $req1=DB::select($sql1); 
    
        return response()->json(['Data'=>$req1]);
     }

}
