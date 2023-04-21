<?php
namespace App\Http\Controllers;
use DB;
use Auth;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(){        
        return view('reports.index_qualify');
    }

    public function getReportApproved($period,$grade){

        $readTmp=DB::SELECT("CALL sp_missedSubjects('$period','$grade')  ");

        $data=[];
        foreach($readTmp as $k=> $v){
           $data['asignatura'][$k]=$v->tag;
           $data['perdidas'][$k]= $v->perdidas;
           $data['aprovadas'][$k]=$v->aprovadas;
        }
        return json_encode($data);
    }
}
