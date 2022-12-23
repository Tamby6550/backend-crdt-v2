<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Utilisateur; 
use App\Identification; 
use DB;


class LoginCrdt extends Controller
{
    public function login(Request $request)
    {
        if ($request->info =='crdtpat') {
            //Jerena sody tsy miexiste le login
            $login=Utilisateur::where("crdtpat.UTILISATEUR.LOGIN", $request->login)->first();
    
            if(!is_null($login)) { 
                //Jerena sody diso ny login sy mot de pass
                $password=Utilisateur::where("crdtpat.UTILISATEUR.LOGIN", $request->login)->where("crdtpat.UTILISATEUR.PASSWORD", $request->password)->first();
                
                // Raha oatraka marina daholo
                if(!is_null($password)) {
                    return response()->json(["status"=>'200', "success"=>true, "succedmsg"=>"Identification succé!! vous êtes connectez","data"=>$password]);
                
                }else{
                    return response()->json(["status" =>"failed", "success"=>false, "message"=>"Mot de passe incorect !" ]);
                }
            }else{
                return response()->json(["status" => "failed", "success" => false, "message" => "Ce matricule n'existe pas ",'data'=>$login]);
            }
        }
        else if ($request->info =='crdtfact') {
             //Jerena sody tsy miexiste le login
             $login=Identification::where("MIANDRALITINA.IDENTIFICATION.LOGIN", $request->login)->first();
    
             if(!is_null($login)) { 
                 //Jerena sody diso ny login sy mot de pass
                 $password=Identification::where("MIANDRALITINA.IDENTIFICATION.LOGIN", $request->login)->where("MIANDRALITINA.IDENTIFICATION.PASSWORD", $request->password)->first();
                 
                 // Raha oatraka marina daholo
                 if(!is_null($password)) {
                     return response()->json(["status"=>'200', "success"=>true, "succedmsg"=>"Identification succé!! vous êtes connectez","data"=>$password]);
                 
                 }else{
                     return response()->json(["status" =>"failed", "success"=>false, "message"=>"Mot de passe incorect !" ]);
                 }
             }else{
                 return response()->json(["status" => "failed", "success" => false, "message" => "Ce matricule n'existe pas ",'data'=>$login]);
             }
        }

    }
}
