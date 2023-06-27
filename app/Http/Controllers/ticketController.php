<?php
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\CapabilityProfile;


namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ticketController extends Controller
{
    public function ticketPrint(){

      // $profile = \Mike42\Escpos\CapabilityProfile\CapabilityProfile::load("simple");
        $nombreImpresora = "POSPrinter";
        $connector = new \Mike42\Escpos\PrintConnectors\WindowsPrintConnector("smb://juanchoscarwash.com/POSPrinter");
        $impresora = new \Mike42\Escpos\Printer($connector);        
      //  $impresora->setJustification(Printer::JUSTIFY_CENTER);
        $impresora->setTextSize(2, 2);
        $impresora->text("Imprimiendo\n");
        $impresora->text("ticket\n");
        $impresora->text("desde\n");
        $impresora->text("Laravel\n");
        $impresora->setTextSize(1, 1);
        $impresora->text("https://parzibyte.me");
        $impresora->feed(5);
        $impresora->close();
    }


}
