<?php
namespace App\Http\Controllers;
use DB;
use DateTime;
use Auth;
use Illuminate\Http\Request;

class ReportsController extends Controller
{

    public  $firstDay,$lastDay;

    public function __construct(){
        $date = new DateTime();
        $this->firstDay =$date->modify('first day of this month')->format('Y-m-d');
        $this->lastDay = $date->modify('last day of  this month')->format('Y-m-d');        
    }

    public function index(){        
        return view('reports.index_qualify');
    }

    public function index_income_expeses(){        
        return view('reports.index_income_expenses');
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

    public function getSalesxMonth(){

       $data=[];
        for ($i=1;$i<=12;$i++){
            $readTmp=DB::SELECT("CALL sp_salesxmonth('$i')  ");            
            $data[]=$readTmp[0]->cantidadxmes;
            
        }      
        return json_encode($data);
    }


    public function getSalesxDay(){                
        $lastDay=date('d',strtotime('last day of this month', time()));
        $data=[];
        for($i=1;$i<=$lastDay;$i++){
            
            $readTmp=DB::SELECT("CALL sp_salesxday('$i')  ");            
            $data['cantidad'][$i]=$readTmp[0]->cantidadxdia;
            $data['label'][$i]=$i;
        }       
       return json_encode($data);
     }



     
     public function chart_income_service(){

        $product=DB::SELECT("CALL sp_incomexproduct()  ");            
        $service=DB::SELECT("CALL sp_incomexservice()  ");            
        
        $data=[];
        $data['product']= is_null($product[0]->gananciasxproducto)? 0:floatval($product[0]->gananciasxproducto);
        $data['service']=is_null($service[0]->gananciasxservicio)? 0: floatval($service[0]->gananciasxservicio);
        $data['total']=number_format(floatval($service[0]->gananciasxservicio)+floatval($product[0]->gananciasxproducto));
              
       return json_encode($data);
     }

     public function dt_expenses_month(){
        
        $expenses=DB::SELECT("CALL sp_expenses()  "); 
        
        
        $data=[];
        $i=0;
        foreach($expenses as $key=> $v){
            $data['data'][$key]['no']=$i++;
            $data['data'][$key]['concepto']= $v->concepto;
            $data['data'][$key]['valor']=number_format($v->egreso);
        }
        return json_encode($data);
       
     }
     
}
