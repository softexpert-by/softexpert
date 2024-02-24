<?php

use App\Models\Form;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return file_get_contents(__DIR__ . '/../resources/pages/index/index.html');
});

Route::get('/form', function () {
    return view('form');
});

Route::post('/submit-form', function(Request $request) {
    info("Got request from IP ".$request->getClientIp());
    $form_json = json_decode($request->getContent(), true);
    $form = new Form();
    $form->email = $form_json['email'];
    $form->position = $form_json['position'];
    $form->fullName = $form_json['fullName'];
    $form->organisation = $form_json['organisation'];
    $form->phoneNumber = $form_json['phoneNumber'];
    $message = "Новый запрос:\nИмя: ".$form->fullName
        ."\nОрганизация: ".$form->organisation."\nДолжность: ".$form->position
        ."\nТелефон:".$form->phoneNumber."\nПочта: ".$form->email;
    $api_key = env('API_TOKEN');
    $chat_id = env('CHAT_ID');
    $base_url = "https://api.telegram.org/";
    $request_url = $base_url."bot".$api_key."/sendMessage?chat_id=".$chat_id."&text=".$message;
    Http::post($request_url);
    return Response("ok");
});

Route::get('/ping', function () {
    return Response("pong");
});
