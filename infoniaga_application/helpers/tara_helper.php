<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if ( ! function_exists('tara_humanize'))
{
    function tara_humanize($str)
    {
        return strtoupper(preg_replace('/[_]+/', ' ', strtolower(trim($str))));
    }
}

if ( ! function_exists('arr_bulan_indonesia'))
{
    function arr_bulan_indonesia()
    {
        return array("1"=>"Januari","2"=>"Februari","3"=>"Maret","4"=>"April","5"=>"Mei","6"=>"Juni","7"=>"Juli","8"=>"Agustus","9"=>"September","10"=>"Oktober","11"=>"Nopember","12"=>"Desember");
    }
}

if ( ! function_exists('arr_hari_indonesia'))
{
    function arr_hari_indonesia()
    {
        return array(1=>"Senin",2=>"Selasa",3=>"Rabu",4=>"Kamis",5=>"Jumat",6=>"Sabtu",7=>"Minggu");
    }
}

/* End of file tara_helper.php */
/* Location: ./application/helpers/tara_helper.php */
?>