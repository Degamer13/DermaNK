<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Récipe - {{ $recipe->codigo }}</title>
    <style>
        /* CONFIGURACIÓN DE PÁGINA */
        @page {
            margin: 1cm 1.5cm;
            margin-bottom: 3cm; /* Espacio reservado para el footer fijo */
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        /* UTILIDAD DE SALTO DE PÁGINA */
        .page-break {
            page-break-after: always;
        }

        /* ENCABEZADO */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .doctor-title {
            font-size: 24px;
            font-weight: bold;
            color: #0056b3;
            margin: 0;
            text-transform: uppercase;
        }
        .doctor-sub {
            font-size: 12px;
            color: #555;
            font-weight: 600;
            letter-spacing: 2px;
            text-transform: uppercase;
        }

        .clinic-info {
            text-align: right;
            font-size: 9px;
            color: #666;
        }
        .clinic-name {
            font-size: 12px;
            font-weight: bold;
            color: #333;
            display: block;
        }

        /* SECCIÓN PACIENTE */
        .patient-section {
            margin-bottom: 20px;
            padding: 10px;
            background-color: #f9f9f9;
            border-radius: 5px;
            border: 1px solid #eee;
        }
        .patient-table { width: 100%; }

        .info-label {
            font-size: 8px;
            color: #888;
            text-transform: uppercase;
            font-weight: bold;
        }
        .info-value {
            font-size: 13px;
            color: #000;
            font-weight: bold;
            display: block;
        }

        /* TÍTULOS DE SECCIÓN */
        .section-title {
            font-size: 18px;
            font-weight: bold;
            color: #0056b3;
            margin-bottom: 10px;
            border-left: 5px solid #0056b3;
            padding-left: 10px;
        }

        /* TABLAS DE ITEMS */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th {
            background-color: #f4f4f4;
            color: #333;
            text-transform: uppercase;
            font-size: 10px;
            padding: 8px;
            text-align: left;
            border-bottom: 2px solid #ddd;
        }
        .items-table td {
            padding: 10px 8px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        .item-index { color: #aaa; font-weight: bold; text-align: center; width: 30px; }
        .med-name { font-size: 14px; font-weight: bold; color: #000; }

        /* Estilo para las indicaciones (Hoja 2) */
        .med-indic-box {
            font-size: 12px;
            color: #444;
            background: #fffcf5;
            padding: 8px;
            border: 1px dashed #ddd;
            border-radius: 4px;
            margin-top: 5px;
        }

        /* OBSERVACIONES */
        .notes-box {
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
            border-radius: 4px;
            font-size: 11px;
            color: #555;
        }

        /* PIE DE PÁGINA (FIJO) */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            height: 100px; /* Altura ajustada */
            background-color: #fff; /* Fondo blanco para no mezclar con texto si se pasa */
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 0 auto 5px auto;
        }
        .doc-name-footer { font-weight: bold; font-size: 12px; }
        .disclaimer {
            font-size: 8px;
            color: #999;
            margin-top: 10px;
        }
    </style>
</head>
<body>

    <div class="footer">
        <div class="signature-line"></div>
        <div class="doc-name-footer">{{ $recipe->paciente->medico }}</div>
        <div style="font-size: 10px;">Dermatología</div>

        <div style="margin-top: 5px;">
             <span style="font-family: monospace; color: #d9534f; font-size: 10px;">REF: {{ $recipe->codigo }}</span>
        </div>

    </div>

    <table class="header-table">
        <tr>
            <td width="55%" style="vertical-align: bottom;">
                <div class="doctor-title">{{ $recipe->paciente->medico ?? 'N/A' }}</div>
                <div class="doctor-sub">Dermatología</div>
            </td>
            <td width="45%" class="clinic-info" style="vertical-align: bottom;">
                <span class="clinic-name" style="color: #0056b3;">Centro Médico Orinoco</span>
                Consultorio N°2 (Detrás de la Farmacia)<br>
                Ciudad Bolívar - Edo. Bolívar<br>
                <strong>Telf:</strong> 0424-9671119
            </td>
        </tr>
    </table>

    <div class="patient-section">
        <table class="patient-table">
            <tr>
                <td width="50%">
                    <div class="info-label">Paciente</div>
                    <span class="info-value">{{ $recipe->paciente->nombre_completo }}</span>
                </td>
                <td width="20%">
                    <div class="info-label">Cédula</div>
                    <span class="info-value">{{ $recipe->paciente->cedula }}</span>
                </td>
                <td width="15%">
                    <div class="info-label">Edad</div>
                    <span class="info-value">{{ $recipe->paciente->fecha_nacimiento->age }} Años</span>
                </td>
                <td width="15%" style="text-align: right;">
                    <div class="info-label">Fecha</div>
                    <span class="info-value">{{ $recipe->fecha->format('d/m/Y') }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Rp. (Medicamentos)</div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="10%">#</th>
                <th width="90%">Medicamento prescrito</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recipe->items as $index => $item)
            <tr>
                <td class="item-index">{{ $index + 1 }}</td>
                <td>
                    <div class="med-name">{{ $item->medicamento }}</div>

                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    @if($recipe->observaciones)
    <div class="notes-box">
        <strong>Nota:</strong> {{ $recipe->observaciones }}
    </div>
    @endif

    <div class="page-break"></div>

    <div style="border-bottom: 1px solid #ccc; margin-bottom: 20px; padding-bottom: 10px;">
        <table width="100%">
            <tr>
                <td>
                    <span style="font-weight: bold; color: #0056b3;">HOJA DE TRATAMIENTO</span><br>
                    <span style="font-size: 10px;">Paciente: {{ $recipe->paciente->nombre_completo }}</span>
                </td>
                <td align="right">
                     <span style="font-size: 10px;">Ref: {{ $recipe->codigo }}</span>
                </td>
            </tr>
        </table>
    </div>

    <div class="section-title">Indicaciones Detalladas</div>

    <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="35%">Medicamento</th>
                <th width="60%">Modo de uso / Indicaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recipe->items as $index => $item)
            <tr>
                <td class="item-index">{{ $index + 1 }}</td>
                <td style="vertical-align: top;">
                    <div class="med-name" style="font-size: 12px;">{{ $item->medicamento }}</div>
                </td>
                <td>
                    <div class="med-indic-box">
                        {{ $item->indicaciones }}
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
