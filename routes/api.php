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
Route::get("getPrescripteurF","App\Http\Controllers\Prescripteur@getPrescripteurF");
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
Route::get("getClientFactF","App\Http\Controllers\ClientFact@getClientFactF");
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
Route::get("rechercheReglementParUser/{indication}","App\Http\Controllers\Reglement@rechercheReglementParUser");


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

// Suppression
Route::delete("deleteRegistre/{num_arriv}&{date_arriv}","App\Http\Controllers\Registre@deleteRegistre");


//----------------------------- Examen Du Jour ------------------------------//
//Examen non effectuer
Route::get("getExamenNonEff","App\Http\Controllers\ExamenDuJour@getExamenNonEff");

//Insertion dans Examens_details
Route::post("insertExamenJour","App\Http\Controllers\ExamenDuJour@insertExamenJour");

//Get examen effectuée
Route::get("getExamenEff","App\Http\Controllers\ExamenDuJour@getExamenEff");

//Get examen effectuée d'un patient
Route::get("getPatientExamenEff/{num_arriv}&{date_arriv}","App\Http\Controllers\ExamenDuJour@getPatientExamenEff");

// Suppression
Route::post("deleteExamenDetails","App\Http\Controllers\ExamenDuJour@deleteExamenDetails");

// Validation et enregistrement Compte Rendu
Route::post("updateExamenDetailsCR","App\Http\Controllers\ExamenDuJour@updateExamenDetailsCR");
Route::put("validationExamen","App\Http\Controllers\ExamenDuJour@validationExamen");


//Get examen effectuée
Route::get("getExamenEffValide","App\Http\Controllers\ExamenDuJour@getExamenEffValide");

//Recherche
Route::post("getRehercheExamenEffValide","App\Http\Controllers\ExamenDuJour@getRehercheExamenEffValide");


//----------------------------- Facture ------------------------------//

//Get Non Facturé
Route::get("getNonFacture","App\Http\Controllers\Facture@getNonFacture");

//Get examen non facture
Route::get("getPatientExamenFacture/{num_arriv}&{date_arriv}","App\Http\Controllers\Facture@getPatientExamenFacture");

//Get idFacture
Route::get("getPageFacture/{num_arriv}&{date_arriv}","App\Http\Controllers\Facture@getPageFacture");

// Changement Tarif
Route::put("changmentTarif","App\Http\Controllers\Facture@changmentTarif");

//Enregistrer
Route::post("insertFacture","App\Http\Controllers\Facture@insertFacture");

//Reglement
Route::post("insertReglementFacture","App\Http\Controllers\Facture@insertReglementFacture");

//Get  Facturé
Route::get("getEffectFacture","App\Http\Controllers\Facture@getEffectFacture");

//Get  Facture réglé
Route::get("getFactureRegler","App\Http\Controllers\Facture@getFactureRegler");

//Get  INFO FACTURE PATIENT
Route::get("getInfoPatientFacture/{num_facture}","App\Http\Controllers\Facture@getInfoPatientFacture");

//Get  INFO FACTURE REGLEMENT
Route::get("getInfoPatientReglementFacture/{num_facture}","App\Http\Controllers\Facture@getInfoPatientReglementFacture");

//Get  LIST REGLEMENTS
Route::get("getListReglementFacture/{num_facture}","App\Http\Controllers\Facture@getListReglementFacture");
Route::get("testAPL","App\Http\Controllers\Facture@testAPL");

// Modifier reglement
Route::put("modifReglementFacture","App\Http\Controllers\Facture@modifReglementFacture");

// Modifier pec remise
Route::put("modifPecRemiseFacture","App\Http\Controllers\Facture@modifPecRemiseFacture");

// Modifier retour facture non regle
Route::put("retourFactNonRegleEnNonPaye","App\Http\Controllers\Facture@retourFactNonRegleEnNonPaye");