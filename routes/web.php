<?php

Route::get('/', function () {
    return view('disabled');
});

Route::get('/backup/report', function () {
    return Backup::all();
});

