<?php

namespace App\Http\Controllers;
use DB;
use App\User;
use Auth;
use Request;
class DocenteController extends Controller
{
    public function index(){
        return view('empleados.index_docentes');
    }
    public function index_create(){
        $roles=DB::SELECT("SELECT id,name FROM roles");
        return view('empleados.index_created',compact('roles'));
    }
    public function sales(){
        return view('empleados.index_sales');
    }
   public function dt_user(){
        $dataUs=User::All();        
        $data=[];
        foreach($dataUs as $key => $us)
        {                            
            $data['data'][$key]['con']=$key;   
            $data['data'][$key]['id']=$us->id;
            $data['data'][$key]['dni']=$us->identificacion;
            $data['data'][$key]['name']=$us->name;   
            $data['data'][$key]['celular']=$us->celular;
            $data['data'][$key]['genero']=$us->genero;                         
            $name=[];
            foreach($us->RolesUser as $i=>$v){
                $name[]=$v->rol->name;
            }         
            $data['data'][$key]['cargo']=  implode($name,' - ');
            $data['data'][$key]['estado']=$us->estado;            
        }      
        return json_encode($data);          
    }

    public function dt_sales_user(){
        $dataUs=DB::SELECT("CALL sp_salesxuser()");        
        $data=[];
        foreach($dataUs as $key => $us)
        {                            
            $data['data'][$key]['con']=$key;   
            $data['data'][$key]['id']=$us->id_user;
            $data['data'][$key]['dni']=$us->identificacion;
            $data['data'][$key]['name']=$us->name; 
            $data['data'][$key]['cant_servicios']=$us->cant_servicios;
            $data['data'][$key]['pagos']=($us->cant_servicios-$us->pendiente); 
            $data['data'][$key]['pendiente']=$us->pendiente; 
            $data['data'][$key]['pend_pago']= (is_null($us->pend_pago))? 0:$us->pend_pago;
           
                       
        }
        return json_encode($data);          
    }

    public function dt_pay_pending($idUser,$status){
        $dataUs=DB::SELECT("CALL sp_cant_sales_user('$idUser','$status')");        
        $data=[];
        $total=0;
        foreach($dataUs as $key => $us)
        {                            
            $data['data'][$key]['no_venta']=$us->no_venta;   
            $data['data'][$key]['nombre_cliente']=$us->nombre_cliente;
            $data['data'][$key]['combo']=$us->combo;
            $data['data'][$key]['vehiculo']= $us->vehiculo;
            $data['data'][$key]['precio_venta']=$us->precio_venta; 
            $data['data'][$key]['porcentaje']=$us->porcentaje;
            $data['data'][$key]['pago']=  round($us->precio_venta*$us->porcentaje/100);
           $total+=round($us->precio_venta*$us->porcentaje/100);
        }
        $data['pay']=$total;
        return json_encode($data);          
    }

    public function pay_sales(){
        DB::table('venta')
                ->where('id_usuario', intval(Request::input('id_usuario')))
                ->where('id_estado_venta', 1)
                ->whereNotNull('id_detalle_paquete')
                ->update([
                    'id_estado_venta' =>2,
                    'fecha_pago' =>date('Y-m-d h:i:s')                    
                    ]
            );
            return 1;
    }
    

    public function create(){

        $user=User::where('identificacion',Request::input('identificacion'))->get();
        if(!isset($user[0]->id)){
            $id=DB::table('users')->insertGetId([
                'identificacion' =>Request::input('identificacion'),
                'name' => Request::input('name'),
                'email'=>Request::input('email'),
                'password'=> \Hash::make('123456'),
                'direccion'=>Request::input('direccion'),
                'telefono'=>Request::input('telefono'),
                'celular'=>Request::input('celular'),                
                'fecha_nacimiento'=>date('Y-m-d',strtotime(Request::input('nacimiento'))),
                'lugar_expedicion'=>Request::input('expedicion'),
                'cargo'=>Request::input('cargo'),
                'genero'=>Request::input('genero')                
            ]);
            $user = User::find($id);
            $user->attachRole(Request::input('cargo'));
            return 1;
        }else{
            return 2;
        }
        
    }
    public function update(){
        DB::table('users')
                ->where('id', Request::input('id_user'))
                ->update([
                    'name' => Request::input('nombre'),
                    'email' => Request::input('email'),
                    'direccion' => Request::input('direccion'),
                    'telefono' => Request::input('telefono'),
                    'celular' => Request::input('celular'),
                    'fecha_nacimiento' => date('Y-m-d',strtotime(Request::input('nacimiento'))),
                    'lugar_expedicion'=>Request::input('expedicion'),
                    'genero'=>Request::input('genero'),
                    'estado'=>Request::input('estado')
                    ]
            );
            return 1;
    }
    public function showUser($id){
        return json_encode(User::find($id));
    }
}
