<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//-----------------------------Login------------------------------//
//Login sur crdt
Route::post("login","App\Http\Controllers\LoginCrdt@login");
// //
// Route::post("activiteCompte","App\Http\Controllers\LoginCrdt@activiteCompteListe");
// //
// Route::get("activite/{nomStructure}","App\Http\Controllers\LoginCrdt@activiteListe");
// //
// Route::delete("activite/{nomactivite}","App\Http\Controllers\LoginCrdt@activiteDelete");
// //
// Route::put("activite/{nomStructure}/{nomActivite}","App\Http\Controllers\LoginCrdt@activiteUpdate");


//-----------------------------Prescripteur------------------------------//
//Insertion prescripteur
Route::post("insertPrescripteur","App\Http\Controllers\Prescripteur@insertPrescripteur");
//GetAll
Route::get("getPrescripteur","App\Http\Controllers\Prescripteur@getPrescripteur");
//Recherche
Route::post("recherchePrescripteur","App\Http\Controllers\Prescripteur@recherchePrescripteur");
// Suppression
Route::delete("deletePrescripteur/{code_presc}","App\Http\Controllers\Prescripteur@deletePrescripteur");
// Modification
Route::put("modifierPrescripteur","App\Http\Controllers\Prescripteur@modifierPrescripteur");



//-----------------------------Examen------------------------------//
//Insertion 
Route::post("insertExamen","App\Http\Controllers\Examen@insertExamen");
//GetAll
Route::get("getAllExamen","App\Http\Controllers\Examen@getAllExamen");
//Recherche
Route::post("rechercheExamen","App\Http\Controllers\Examen@rechercheExamen");
// Suppression
Route::delete("deleteExamen/{id_exam}","App\Http\Controllers\Examen@deleteExamen");
// Modification
Route::put("updateExamen","App\Http\Controllers\Examen@updateExamen");
//Recheche par Tarif 
Route::get("rechercheExamParTarif/{tarif}","App\Http\Controllers\Examen@rechercheExamParTarif");


//-----------------------------Patient------------------------------//
//Insertion 
Route::post("insertPatient","App\Http\Controllers\Patient@insertPatient");
//GetAll
Route::get("getPatient","App\Http\Controllers\Patient@getPatient");
Route::get("affichePatient/{id_patient}","App\Http\Controllers\Patient@affichePatient");
//Recherche
Route::post("recherchePatient","App\Http\Controllers\Patient@recherchePatient");
// Suppression
Route::delete("deletePatient/{id_patient}","App\Http\Controllers\Patient@deletePatient");
// Modification
Route::put("updatePatient","App\Http\Controllers\Patient@updatePatient");


//-----------------------------Consultation------------------------------//
//Insertion 
Route::post("insertConsultation","App\Http\Controllers\Consultation@insertConsultation");
//GetAll
Route::get("getConsultation/{id_patient}","App\Http\Controllers\Consultation@getConsultation");
//Get examen rapport du jour
Route::get("getRapportExamenDetails/{daty}","App\Http\Controllers\Consultation@getRapportExamenDetails");
//Get examen rapport par patient
Route::get("getRapportExamenDetailsPatient/{id_patient}&{date_deb}&{date_fin}","App\Http\Controllers\Consultation@getRapportExamenDetailsPatient");
// Suppression
Route::delete("deleteConsultation/{id_consult}","App\Http\Controllers\Consultation@deleteConsultation");
// Modification facture ou ajout facture
Route::put("ajoutFactureMontant","App\Http\Controllers\Consultation@ajoutFactureMontant");
// Modification document ou ajout document
Route::post("ajoutFichierDoc","App\Http\Controllers\Consultation@ajoutFichierDoc");


//-----------------------------CRDT Facture Tamby------------------------------//


//----------------------------- Client ------------------------------//
//Insertion 
Route::post("insertClient","App\Http\Controllers\ClientFact@insertClient");
//GetAll
Route::get("getClientFact","App\Http\Controllers\ClientFact@getClientFact");
// Recherche Client Fact 
Route::post("rechercheClientFact","App\Http\Controllers\ClientFact@rechercheClientFact");
// Suppression
Route::delete("deleteClientFact/{code_cli}","App\Http\Controllers\ClientFact@deleteClientFact");
// Modification facture ou ajout facture
Route::put("updateClientFact","App\Http\Controllers\ClientFact@updateClientFact");


//-----------------------------Prescripteur Fact------------------------------//
//Insertion prescripteur
Route::post("insertPrescripteur","App\Http\Controllers\Prescripteur@insertPrescripteur");
//GetAll
Route::get("getPrescripteurFact","App\Http\Controllers\Prescripteur@getPrescripteurFact");
//Recherche
Route::post("recherchePrescripteur","App\Http\Controllers\Prescripteur@recherchePrescripteur");
// Suppression
Route::delete("deletePrescripteur/{code_presc}","App\Http\Controllers\Prescripteur@deletePrescripteur");
// Modification
Route::put("modifierPrescripteur","App\Http\Controllers\Prescripteur@modifierPrescripteur");



//----------------------------- Reglement Fact ------------------------------//
//Insertion reglement
Route::post("insertReglement","App\Http\Controllers\Reglement@insertReglement");
//GetAll
Route::get("getAllReglementFact","App\Http\Controllers\Reglement@getAllReglementFact");
//Recherche
Route::post("rechercheReglementFact","App\Http\Controllers\Reglement@rechercheReglementFact");
// Modification
Route::put("updateReglementFact","App\Http\Controllers\Reglement@updateReglementFact");
// Suppression
Route::delete("deleteReglementFact/{code_presc}","App\Http\Controllers\Reglement@deleteReglementFact");



//----------------------------- Saisie Reglement Fact ------------------------------//
//GetAll
Route::get("getSaisieReglementFact","App\Http\Controllers\SaisieReglement@getSaisieReglementFact");
//Recherche saisie reglement
Route::post("rechercheSaisieReglement","App\Http\Controllers\SaisieReglement@rechercheSaisieReglement");
//Affiche details saisie reglement
Route::post("afficheDetailsSaisieReglement","App\Http\Controllers\SaisieReglement@afficheDetailsSaisieReglement");
//Insertion details saisie reglement
Route::post("insertReglementDetails","App\Http\Controllers\SaisieReglement@insertReglementDetails");
//Affiche list paiment details saisie reglement
Route::post("affichePaimentDetailsReglmnt","App\Http\Controllers\SaisieReglement@affichePaimentDetailsReglmnt");


//----------------------------- Information Registre du jour ------------------------------//
//Maka numéro registre
Route::get("getNumArriv","App\Http\Controllers\Registre@getNumArriv");

//Insertion dans registre
Route::post("insertRegistre","App\Http\Controllers\Registre@insertRegistre");

//Get Listeregistre
Route::get("getListRegistre","App\Http\Controllers\Registre@getListRegistre");

// Modification N°Journal
Route::put("updateRegistre","App\Http\Controllers\Registre@updateRegistre");

//Recherche
Route::post("rechercheRegistre","App\Http\Controllers\Registre@rechercheRegistre");