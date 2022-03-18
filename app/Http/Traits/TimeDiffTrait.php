<?php

namespace App\Http\Traits;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\{
    Work_time
};


trait TimeDiffTrait
{
    public function sumAllTimeDiff($task_id)
    {
        $time_data = Work_time::where('user_id', Auth::id())
            ->where('task_id', $task_id)
            ->whereNull('deleted_at')
            ->select('minute', 'hour')
            ->get();

        $sum_hour_to_minute = 0;
        $sum_minute = 0;
        foreach($time_data as $arr) {
            $sum_hour_to_minute += ($arr->hour * 60);
        }
        foreach ($time_data as $arr) {
            $sum_minute += $arr->minute;
        }
        $total_minute = ($sum_hour_to_minute + $sum_minute);

        return $total_minute;
    }

    public function timeDiff($exe_time, $sus_time)
    {
        $diff_time = array();

        //日付データを秒に変換
        $exeTime = strtotime($exe_time);
        $susTime = strtotime($sus_time);

        $dif_seconds =  $susTime - $exeTime;

        $dif_minutes = ($dif_seconds - ($dif_seconds % 60)) / 60;
        $dif_hours = ($dif_minutes - ($dif_minutes % 60)) / 60;

        // $diffTime['seconds'] = $dif_seconds % 60;
        $diff_time['minutes'] = $dif_minutes % 60;
        $diff_time['hours'] = $dif_hours;

        return $diff_time;
    }

    public function totalDiff($total_minute)
    {
        $diff_time = array();

        $total_hours = ($total_minute - ($total_minute % 60)) / 60;

        $diff_time['minutes'] = $total_minute % 60;
        $diff_time['hours'] = $total_hours;

        return $diff_time;
    }
}

?>
