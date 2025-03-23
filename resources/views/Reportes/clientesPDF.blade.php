<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Ordenes</title>
    <style>
        @page { margin: 40px 50px;}
       
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 10px;
        }
        .header { text-align: center; margin-top: 20px;}
        .header img {width: 80px;}
        .header h2 { margin: 5px 0; font-size: 18px;}
        .header p { margin: 2px 0; font-size: 14px;}
        .tabla {width: 100%; border-collapse: collapse; margin-top: 10px;}
        .tabla th, .tabla td {border: 1px solid black; padding: 8px; text-align: left;}
        .tabla th {background-color: black; color: white;}
        .footer{font-weight: bold; color: blue;}
    </style>
</head>
<body>
    <div class="header">
        <img src="{{public_path('images/logo1.jpg')}}" alt="logo">
        <h2>EMPRESA RentaCar S.A de C.V</h2>
        <p>Desde: {{ $fecha1 }} - Hasta: {{ $fecha2 }}</p>
    </div>

    @foreach ($data as $cliente)
    <table class="tabla">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Apellido</th>
                <th>Fecha de nacimiento</th>
                <th>Dirección</th>
                <th>Teléfono</th>
            </tr>
        </thead>
        <tbody>
           
            <tr> 
                <td>{{ $cliente->nombre }}</td>
                <td>{{ $cliente->apellido }}</td>
                <td>{{ $cliente->fecha_nacimiento }}</td>
                <td>{{ $cliente->direccion }}</td>
                <td>{{ $cliente->telefono }}</td>
            </tr>

        </tbody>
    </table>

    @endforeach 
    
    <hr style="margin-top:16px">
    <p class="footer">Total de clientes nuevos en el periodo: {{count($data)}}</p>

    <script type="text/php">
        if(isset($pdf)) {
            $pdf->page_script('
                $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                $pdf->text(270, 800, "Página $PAGE_NUM de $PAGE_COUNT", $font, 10);
            ');
        }
    </script>
    

</body>
</html>