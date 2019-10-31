<?php
function unique_code()
{
    $lastRecord = \Illuminate\Support\Facades\DB::table('users')->get()->last();
    $lastRecord ? $id = $lastRecord->id + 1 : $id = 0;
    return strtoupper(substr(base_convert(sha1(uniqid(mt_rand())), 16, 36), 0, 6)) . $id;
}
?>