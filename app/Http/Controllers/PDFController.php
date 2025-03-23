<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\PDF as PDF;

class PDFController extends Controller
{
    public function getClientes(Request $request) {
        //obteniendo los parametros del $request
        $fecha1 = $request->fechaInicio ?? now()->startOfMonth()->toDateString();
        $fecha2 = $request->fechaFinal ?? now()->startOfMonth()->toDateString();
        
        $clientes = DB::select(
        "SELECT c.nombre, c.apellido, c.fecha_nacimiento, c.direccion, c.telefono 
        FROM clientes c 
        WHERE DATE(c.created_at) BETWEEN ? AND ? 
        ORDER BY c.id DESC"          
        ,[$fecha1, $fecha2]);
        $data = collect($clientes);


        //generra el PDF con los datos obtenidos
    
         $pdf = \PDF::loadView('reportes.clientesPDF',compact('data', 'fecha1','fecha2'));
        return $pdf->stream('reporte_clientes.pdf');
 /* 
        return response()->json($data); */
    }
    
}
