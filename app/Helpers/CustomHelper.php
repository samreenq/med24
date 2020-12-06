<?php

function translateArr($record,$lang = 'en')
{
    foreach ($record as $key => $arr){
        $return[$key] = (is_array($arr) && isset($arr[$lang])) ? $arr[$lang] : $arr;

    }
    return $return;
}

function translateArray($records,$lang = 'en')
{
    $returns = array();
    foreach ($records as $record){

        foreach ($record as $key => $arr){
            $return[$key] = (is_array($arr) && isset($arr[$lang])) ? $arr[$lang] : $arr;

        }
        $returns[] =  $return;
    }
    return $returns;

}

function avgRating($records)
{
    $total = 0;
    if(count($records)>0){
        foreach ($records as $record){
            $total += $record['rating'];
        }
        return round($total/count($records),2);
    }
}

function limitStr($string,$limit = 115)
{
    if(strlen($string) >= $limit) {
        $string =  substr($string, 0, $limit) . '...';
        }
    return $string;
}

function truncateString($str, $chars, $to_space, $replacement="...") {
    if($chars > strlen($str)) return $str;

    $str = substr($str, 0, $chars);
    $space_pos = strrpos($str, " ");
    if($to_space && $space_pos >= 0)
        $str = substr($str, 0, strrpos($str, " "));

    return($str . $replacement);
}

function convert($datetime){
    $time=strtotime($datetime);
    $diff=time()-$time;
    $diff/=60;
    $var1=floor($diff);
    $var=$var1<=1 ? 'min' : 'mins';
    if($diff>=60){
        $diff/=60;
        $var1=floor($diff);
        $var=$var1<=1 ? 'hr' : 'hrs';
        if($diff>=24){$diff/=24;$var1=floor($diff);$var=$var1<=1 ? 'day' : 'days';
            if($diff>=30.4375){$diff/=30.4375;$var1=floor($diff);$var=$var1<=1 ? 'month' : 'months';
                if($diff>=12){$diff/=12;$var1=floor($diff);$var=$var1<=1 ? 'year' : 'years';}}}}
    echo $var1,' ',$var,' ago';
}



