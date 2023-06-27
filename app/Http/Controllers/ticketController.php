<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ticketController extends Controller
{
    public function ticketPrint()
    {

      $title="No.";
      $GAA="Ticket-";    
      $customPaper = array(0,0,500,800);    
      //'b6', 'portrait'
      $pdf = \PDF::loadView('ticket.pdf_ticket',compact('title'))->setPaper($customPaper)->stream($GAA.".pdf");
      return $pdf;
     
    }

}
