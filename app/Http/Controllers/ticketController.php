<?php
namespace App\Http\Controllers;
use App\Model\Venta;
use Illuminate\Http\Request;

class ticketController extends Controller
{
    public function ticketPrint($id)
    {

      $venta=Venta::find($id);   
      //dd($venta->detalle_paquete->tipo_vehiculo->descripcion);
      $GAA="Ticket-". $id;    
      $customPaper = array(0,0,500,850);    
      $pdf = \PDF::loadView('ticket.pdf_ticket',compact('venta'))->setPaper($customPaper)->stream($GAA.".pdf");
      return $pdf;
     
    }

}
