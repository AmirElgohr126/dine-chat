<?php

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

Route::get('/privacy', function () {
    return view('Privacy.Privacy');
});
Route::get('/table', function () {
    return view('tables');
});
Route::get('/new', function () {
    return view('new');
});

Route::get('/terms', function () {
    return view('Privacy.Terms');
});
// <script type="module">
//   // Import the functions you need from the SDKs you need
//   import { initializeApp } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-app.js";
//   import { getAnalytics } from "https://www.gstatic.com/firebasejs/10.7.1/firebase-analytics.js";
//   // TODO: Add SDKs for Firebase products that you want to use
//   // https://firebase.google.com/docs/web/setup#available-libraries

//   // Your web app's Firebase configuration
//   // For Firebase JS SDK v7.20.0 and later, measurementId is optional
//   const firebaseConfig = {
//     apiKey: "AIzaSyDeMXoFuYzejgmkqd0EZRjj1xhX9IM0YYM",
//     authDomain: "dine-chat.firebaseapp.com",
//     projectId: "dine-chat",
//     storageBucket: "dine-chat.appspot.com",
//     messagingSenderId: "451440813790",
//     appId: "1:451440813790:web:e3584a95b18c08da72444f",
//     measurementId: "G-J7L5234MET"
//   };

//   // Initialize Firebase
//   const app = initializeApp(firebaseConfig);
//   const analytics = getAnalytics(app);
// </script>

// Route::post('testserver', function () {
//     $response = sendEvent('my-channel', 'UpdateUserHall', );
//     return $response;
// });
