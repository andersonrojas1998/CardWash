<?php

namespace App\Http\Controllers;
use DB;
use App\User;
use Auth;
use Request;
class DocenteController extends Controller
{
    public function index(){
        return view('docentes.index_docentes');
    }
    public function index_create(){
        $roles=DB::SELECT("SELECT id,name FROM roles");
        return view('docentes.index_created',compact('roles'));
    }
   /* public function showTeacher($status=1){
        $dataUs=DB::select("SELECT tb1.id,tb1.name FROM users as tb1  
                            INNER JOIN role_user AS tb2 on tb1.id=tb2.user_id
                            INNER JOIN roles as tb3 on tb2.role_id=tb3.id
                            WHERE tb1.estado='$status' AND  tb3.slug='docente' ");        
        return json_encode($dataUs);
    }*/

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
            $cargos=DB::SELECT("SELECT tb3.name FROM role_user AS tb2 INNER JOIN roles as tb3 on tb2.role_id=tb3.id WHERE  tb2.user_id='$us->id' ");            
            $name=[];
            foreach($cargos as $i=>$v){
                $name[]=$v->name;
            }            
            $data['data'][$key]['cargo']= implode($name,' - ');
            $data['data'][$key]['estado']=$us->estado;            
        }
        return json_encode($data);          
    }
    /*public function gradeAssignments(){        
        $idTeacher = Auth::user()->id;
        $gradesAll=DB::SELECT("SELECT distinct A.id_grado, concat(A.grupo,'  ',C.nombre) as grupo FROM  grado as A
                    inner join curso AS B ON A.id_grado=B.id_grado
                    inner join jornada as C ON A.id_jornada=C.id_jornada         
                    where B.id_docente='$idTeacher' ");
        return json_encode($gradesAll);
    }*/
   /* public function assignmentCourseTeacher(){
        $res=Request::all();             
        $teacher=$res['idTeacher'];
        $grade=$res['idgrade'];
        $course=DB::SELECT("SELECT A.id_asignatura,A.nombre from asignatura as A inner join curso AS B ON A.id_asignatura=B.id_materia where B.id_docente='$teacher' AND B.id_grado='$grade' ");
        return json_encode($course);
    }*/
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
