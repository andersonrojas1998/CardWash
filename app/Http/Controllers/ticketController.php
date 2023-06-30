<?php
namespace App\Http\Controllers;
use App\Model\Venta;
use Illuminate\Http\Request;

class ticketController extends Controller
{
    public function ticketPrint(Venta $venta)
    {

      $title="No.";
      $GAA="Ticket-";    
      $customPaper = array(0,0,500,850);    
      //'b6', 'portrait'
      $pdf = \PDF::loadView('ticket.pdf_ticket',compact('venta'))->setPaper($customPaper)->stream($GAA.".pdf");
      return $pdf;
     
    }

}
