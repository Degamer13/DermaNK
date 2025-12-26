<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Récipe - {{ $recipe->codigo }}</title>
    <style>
        /* CONFIGURACIÓN DE PÁGINA */
        @page {
            margin: 1cm 1.5cm; /* Márgenes de la hoja */
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.4;
        }

        /* ENCABEZADO */
        .header-table {
            width: 100%;
            border-bottom: 2px solid #0056b3; /* Línea azul base */
            padding-bottom: 15px;
            margin-bottom: 25px;
        }

        .doctor-title {
            font-size: 26px;
            font-weight: bold;
            color: #0056b3;
            letter-spacing: -0.5px;
            margin: 0;
            text-transform: uppercase;
        }
        .doctor-sub {
            font-size: 14px;
            color: #555;
            font-weight: 600;
            letter-spacing: 2px; /* Espaciado elegante */
            text-transform: uppercase;
        }

        .clinic-info {
            text-align: right;
            font-size: 10px;
            color: #666;
        }
        .clinic-name {
            font-size: 14px;
            font-weight: bold;
            color: #333;
            margin-bottom: 5px;
            display: block;
        }

        /* SECCIÓN PACIENTE (Estilo Tarjeta Limpia) */
        .patient-section {
            margin-bottom: 30px;
        }
        .patient-table { width: 100%; }

        .info-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            font-weight: bold;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 14px;
            color: #000;
            font-weight: bold;
            border-bottom: 1px solid #eee; /* Línea sutil debajo del dato */
            padding-bottom: 2px;
            display: block;
        }

        /* TABLA DE MEDICAMENTOS (RX) */
        .rx-header {
            font-size: 24px;
            font-weight: bold;
            font-family: serif; /* Estilo clásico Rx */
            color: #0056b3;
            margin-bottom: 10px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 5px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
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
            padding: 12px 8px;
            border-bottom: 1px solid #f0f0f0;
            vertical-align: top;
        }

        /* Números de lista */
        .item-index { color: #aaa; font-weight: bold; text-align: center; }

        /* Nombre del medicamento */
        .med-name { font-size: 14px; font-weight: bold; color: #000; }

        /* Indicaciones */
        .med-indic {
            font-size: 12px;
            color: #555;
            margin-top: 4px;
            font-style: italic;
            background: #fdfdfd;
            padding: 5px;
            border-left: 2px solid #0056b3; /* Barrita azul al lado de la indicación */
        }

        /* OBSERVACIONES */
        .notes-box {
            margin-top: 30px;
            border: 1px dashed #ccc;
            padding: 10px;
            border-radius: 4px;
            font-size: 11px;
            color: #555;
            background-color: #fffcf5; /* Un toque cálido muy suave */
        }

        /* PIE DE PÁGINA */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            height: 120px;
        }
        .signature-line {
            width: 200px;
            border-top: 1px solid #000;
            margin: 0 auto 5px auto;
        }
        .doc-name-footer { font-weight: bold; font-size: 13px; }
        .disclaimer {
            font-size: 9px;
            color: #999;
            margin-top: 15px;
        }

        /* Utilidad para código de barras o QR si quisieras a futuro */
        .meta-info {
            font-family: monospace;
            color: #d9534f;
            font-size: 12px;
            text-align: right;
        }
    </style>
</head>
<body>

    <table class="header-table">
        <tr>
            <td width="55%" style="vertical-align: bottom;">
                <div class="doctor-title">{{ $recipe->paciente->medico ?? 'N/A' }}</div>
                <div class="doctor-sub">Dermatología</div>
            </td>

            <td width="45%" class="clinic-info" style="vertical-align: bottom;">
                <span class="clinic-name" style="color: #0056b3;">Centro Médico Orinoco</span>
                Planta Baja, Consultorio N°2 (Detrás de la Farmacia)<br>
                Ciudad Bolívar - Edo. Bolívar<br>
                <strong>Telf:</strong> 0285-6327138 / 0424-9671119<br>
                <strong>IG:</strong> &#64;dranormakawan <br>
                <strong>Email:</strong> Normakch@hotmail.com
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

    <div class="rx-header">Rp.</div> <table class="items-table">
        <thead>
            <tr>
                <th width="5%">#</th>
                <th width="45%">Medicamento</th>
                <th width="50%">Indicaciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($recipe->items as $index => $item)
            <tr>
                <td class="item-index">{{ $index + 1 }}.</td>
                <td>
                    <div class="med-name">{{ $item->medicamento }}</div>
                </td>
                <td>
                    <div class="med-indic">
                        {{ $item->indicaciones }}
                    </div>
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

    <div class="footer">
        <div style="height: 50px;"></div>

        <div class="signature-line"></div>
        <div class="doc-name-footer">{{ $recipe->paciente->medico }}</div>
        <div style="font-size: 11px;">Dermatología</div>

        <div style="margin-top: 10px; border-top: 1px dotted #ccc; padding-top: 5px;">
             <span style="font-family: monospace; color: #d9534f; font-size: 10px;">REF: {{ $recipe->codigo }}</span>
        </div>

        <div class="disclaimer">
            Validez: 30 días continuos. Documento generado el {{ now()->format('d/m/Y h:i A') }}
        </div>
    </div>

</body>
</html>
