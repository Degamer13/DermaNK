<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Récipe - {{ $recipe->codigo }}</title>
    <style>
        /* 1. CONFIGURACIÓN DE PÁGINA (MEDIA CARTA) */
        @page {
            size: 14cm 21.6cm; /* Ancho x Alto */
            margin: 1cm; /* Márgenes seguros */
            margin-bottom: 2.5cm; /* Espacio para el footer */
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 11px; /* Letra legible pero compacta */
            color: #000;
            line-height: 1.3;
        }

        /* UTILIDADES */
        .page-break { page-break-after: always; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .bold { font-weight: bold; }
        .uppercase { text-transform: uppercase; }

        /* ENCABEZADO SIMPLE */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #000; /* Línea negra simple */
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .doc-name { font-size: 14px; font-weight: bold; }
        .doc-spec { font-size: 10px; }
        .clinic-data { font-size: 9px; line-height: 1.2; }

        /* DATOS DEL PACIENTE (Estilo lineal limpio) */
        .patient-box {
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
            margin-bottom: 15px;
        }
        .patient-table { width: 100%; font-size: 10px; }
        .label { font-weight: bold; margin-right: 5px; }

        /* LISTAS DE MEDICAMENTOS */
        .med-list {
            width: 100%;
            border-collapse: collapse;
        }
        .med-list td {
            padding: 5px 0;
            vertical-align: top;
        }
        .med-number {
            width: 20px;
            font-weight: bold;
        }
        .med-name {
            font-size: 12px;
            font-weight: bold;
        }
        .med-indication {
            font-size: 11px;
            margin-top: 2px;
            font-style: italic;
            color: #333;
            padding-left: 0;
        }

        /* SEPARADORES ENTRE ITEMS (Opcional, para limpieza) */
        .item-row {
            border-bottom: 1px dotted #ccc;
        }

        /* FOOTER FIJO */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            text-align: center;
            font-size: 9px;
            background: white;
        }
        .signature-line {
            width: 150px;
            border-top: 1px solid #000;
            margin: 0 auto 2px auto;
        }
    </style>
</head>
<body>

    <div class="footer">
        <div class="signature-line"></div>
        <div class="bold">{{ $recipe->paciente->medico }}</div>
        <div>Dermatología</div>
        <div style="margin-top: 5px;">Ref: {{ $recipe->codigo }}</div>
    </div>

    <table class="header-table">
        <tr>
            <td width="60%" valign="bottom">
                <div class="doc-name uppercase">{{ $recipe->paciente->medico ?? 'N/A' }}</div>
                <div class="doc-spec uppercase">Dermatología</div>
            </td>
            <td width="40%" class="text-right clinic-data" valign="bottom">
                <span class="bold">Centro Médico Orinoco</span><br>
                Consultorio N°2<br>
                Telf: 0424-9671119
            </td>
        </tr>
    </table>

    <div class="patient-box">
        <table class="patient-table">
            <tr>
                <td><span class="label">Paciente:</span> {{ $recipe->paciente->nombre_completo }}</td>
                <td><span class="label">C.I:</span> {{ $recipe->paciente->cedula }}</td>
                <td class="text-right"><span class="label">Fecha:</span> {{ $recipe->fecha->format('d/m/Y') }}</td>
            </tr>
        </table>
    </div>

    <div class="bold" style="font-size: 14px; margin-bottom: 10px;">Rp. (Medicamentos)</div>

    <table class="med-list">
        @foreach($recipe->items as $index => $item)
        <tr class="item-row">
            <td class="med-number">{{ $index + 1 }}.</td>
            <td>
                <div class="med-name">{{ $item->medicamento }}</div>
                </td>
        </tr>
        @endforeach
    </table>

    @if($recipe->observaciones)
    <div style="margin-top: 20px; font-size: 10px; border: 1px solid #000; padding: 5px;">
        <span class="bold">Observación:</span> {{ $recipe->observaciones }}
    </div>
    @endif

    <div class="page-break"></div>

    <div style="border-bottom: 2px solid #000; margin-bottom: 15px; padding-bottom: 5px;">
        <table width="100%">
            <tr>
                <td class="bold uppercase" style="font-size: 12px;">Plan de Tratamiento</td>
                <td class="text-right" style="font-size: 10px;">Paciente: {{ $recipe->paciente->nombre_completo }}</td>
            </tr>
        </table>
    </div>

    <table class="med-list">
        @foreach($recipe->items as $index => $item)
        <tr> <td class="med-number" style="padding-top: 10px;">{{ $index + 1 }}.</td>
            <td style="padding-top: 10px;">
                <div class="med-name">{{ $item->medicamento }}</div>
                <div class="med-indication">
                    {{ $item->indicaciones }}
                </div>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="border-bottom: 1px solid #eee;"></td>
        </tr>
        @endforeach
    </table>

</body>
</html>
