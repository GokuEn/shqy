<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/setmission', function () {
    return view('ueditortest');
});
Route::any('/','ViewController@index');
Route::get('/missionlist','ViewController@missionList');
Route::any('/mission','ViewController@mission');
Route::any('/setquest','ViewController@setQuest');
Route::any('/infinityview','ViewController@infinityView');
Route::any('/lesson','ViewController@lesson');
Route::any('/jielong','ViewController@jielong');
Route::any('/practice','ViewController@practice');











Route::get('/addusercode','ServiceController@addUser');
Route::any('/checkcode','ServiceController@checkCode');
Route::any('/changenickname','ServiceController@changeNickname');
Route::any('/login','ServiceController@login');
Route::any('/setmissioncontent','ServiceController@setMissionContent');
Route::any('/getmissioncontent','ServiceController@getMissionContent');
Route::any('/getmissionname','ServiceController@getMissionName');
Route::any('/createquest','ServiceController@createQuest');
Route::any('/addselection','ServiceController@addSelection');
Route::any('/changequestcontent','ServiceController@changeQuestContent');
Route::any('/showquest','ServiceController@showQuest');
Route::any('/getquestcard','ServiceController@getQuestCard');
Route::any('/getmissionlist','ServiceController@getMissionList');
Route::any('/createteam','ServiceController@createTeam');
Route::any('/jointeam','ServiceController@joinTeam');
Route::any('/infinity','ServiceController@infinity');
Route::any('/lessonact','ServiceController@lessonAct');
Route::any('/testact','ServiceController@testAct');
Route::any('/daboss','ServiceController@daBoss');
Route::any('/dopractice','ServiceController@doPractice');