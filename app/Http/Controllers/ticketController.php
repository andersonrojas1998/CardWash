<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ticketController extends Controller
{
    public function ticketPrint()
    {

      $title="No.";
      $GAA="Ticket-";    
      $customPaper = array(80,150);    
      $pdf = \PDF::loadView('ticket.pdf_ticket',compact('title'))->setPaper('b7', 'portrait')->stream($GAA.".pdf");
      return $pdf;
     
    }

}
