<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Historia {{ $historia->codigo_historia }} - {{ $historia->cedula }}</title>
    <style>
        /* CONFIGURACIÓN GENERAL */
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        /* ENCABEZADO */
        .header-table {
            width: 100%;
            border-bottom: 3px solid #0056b3;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .clinic-name {
            font-size: 18px;
            font-weight: bold;
            color: #0056b3;
            text-transform: uppercase;
        }
        .clinic-sub {
            font-size: 10px;
            color: #555;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        .historia-box {
            text-align: right;
        }
        .historia-number {
            font-size: 16px;
            font-weight: bold;
            color: #d9534f; /* Rojo para destacar el número */
            border: 2px solid #d9534f;
            padding: 5px 10px;
            display: inline-block;
            border-radius: 4px;
        }

        /* TÍTULOS DE SECCIÓN */
        .section-header {
            background-color: #f0f4f8; /* Gris azulado muy claro */
            color: #004085;
            padding: 6px 10px;
            font-weight: bold;
            border-top: 1px solid #004085;
            border-bottom: 1px solid #004085;
            margin-top: 15px;
            margin-bottom: 10px;
            text-transform: uppercase;
            font-size: 10px;
        }

        /* TABLAS DE DATOS */
        .data-table {
            width: 100%;
            border-collapse: collapse;
        }
        .data-table td {
            padding: 5px;
            vertical-align: top;
        }

        /* ETIQUETAS Y VALORES */
        .label {
            font-weight: bold;
            color: #666;
            font-size: 9px;
            text-transform: uppercase;
            width: 1%; /* Truco para que la celda se ajuste al texto */
            white-space: nowrap;
            padding-right: 10px;
        }
        .value {
            color: #000;
            font-weight: normal;
            border-bottom: 1px dotted #ccc; /* Línea de puntos para guiar la vista */
        }

        /* TABLA DE PATOLOGÍAS */
        .pat-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .pat-table th {
            background-color: #e9ecef;
            text-align: left;
            padding: 6px;
            font-size: 9px;
            border-bottom: 2px solid #ccc;
            text-transform: uppercase;
        }
        .pat-table td {
            padding: 8px 6px;
            border-bottom: 1px solid #eee;
        }

        /* FOOTER */
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9px;
            color: #aaa;
            border-top: 1px solid #ddd;
            padding-top: 5px;
        }

        /* UTILIDADES */
        .full-width { width: 100%; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

    <div class="footer">
        Documento generado el {{ date('d/m/Y h:i A') }} • Sistema de Gestión Dermatológica
    </div>

    <table class="header-table">
        <tr>
            <td width="60%">
                <div class="clinic-name">Centro Médico Orinoco</div>
                <div class="clinic-sub">Servicio de Dermatología</div>
                <div style="font-size: 10px; margin-top: 5px;">
                    Dr(a). {{ $historia->medico }}
                </div>
            </td>
            <td width="40%" class="historia-box">
                <div style="font-size: 9px; color: #777; margin-bottom: 2px;">NÚMERO DE HISTORIA</div>
                <div class="historia-number">{{ $historia->codigo_historia }}</div>
            </td>
        </tr>
    </table>

    <div class="section-header">I. Datos de Identificación del Paciente</div>

    <table class="data-table">
        <tr>
            <td class="label">Apellidos:</td>
            <td class="value" width="40%">{{ $historia->apellidos }}</td>

            <td class="label">Nombres:</td>
            <td class="value" width="40%">{{ $historia->nombres }}</td>
        </tr>
    </table>

    <table class="data-table" style="margin-top: 5px;">
        <tr>
            <td class="label">Cédula:</td>
            <td class="value">{{ $historia->cedula }}</td>

            <td class="label">Fecha Nacimiento:</td>
            <td class="value">{{ $historia->fecha_nacimiento->format('d/m/Y') }}</td>

            <td class="label">Edad:</td>
            <td class="value">{{ $historia->fecha_nacimiento->age }} Años</td>
        </tr>
    </table>

    <table class="data-table" style="margin-top: 5px;">
        <tr>
            <td class="label">Lugar Nacimiento:</td>
            <td class="value">{{ $historia->lugar_nacimiento }}</td>

            <td class="label">Género:</td>
            <td class="value">{{ $historia->genero }}</td>

            <td class="label">Estado Civil:</td>
            <td class="value">{{ $historia->estado_civil }}</td>
        </tr>
    </table>

    <div class="section-header">II. Ubicación y Contacto</div>

    <table class="data-table">
        <tr>
            <td class="label">Dirección de Habitación:</td>
            <td class="value">{{ $historia->direccion }}</td>
        </tr>
    </table>

    <table class="data-table" style="margin-top: 5px;">
        <tr>
            <td class="label">Teléfono Móvil:</td>
            <td class="value">{{ $historia->telefono }}</td>

            <td class="label">Teléfono Casa:</td>
            <td class="value">{{ $historia->telefono_casa ?? 'N/A' }}</td>

            <td class="label">Correo Electrónico:</td>
            <td class="value">{{ $historia->email ?? 'No registrado' }}</td>
        </tr>
    </table>

    <div class="section-header">III. Información Socioeconómica y Administrativa</div>

    <table class="data-table">
        <tr>
            <td class="label">Ocupación:</td>
            <td class="value">{{ $historia->ocupacion }}</td>

            <td class="label">Profesión:</td>
            <td class="value">{{ $historia->profesion ?? 'N/A' }}</td>
        </tr>
    </table>

    <table class="data-table" style="margin-top: 5px;">
        <tr>
            <td class="label">Seguro Médico:</td>
            <td class="value">{{ $historia->seguro }}</td>

            <td class="label">Referido por:</td>
            <td class="value">{{ $historia->referido ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Fecha de Registro:</td>
            <td class="value">{{ $historia->created_at->format('d/m/Y h:i A') }}</td>

            <td class="label">Médico Tratante:</td>
            <td class="value">{{ $historia->medico }}</td>
        </tr>
    </table>

    <div class="section-header">IV. Antecedentes Patológicos Registrados</div>

    @if($historia->patologias->count() > 0)
        <table class="pat-table">
            <thead>
                <tr>
                    <th width="5%">#</th>
                    <th width="35%">Nombre de la Patología</th>
                    <th width="60%">Observaciones / Detalles</th>
                </tr>
            </thead>
            <tbody>
                @foreach($historia->patologias as $index => $patologia)
                <tr>
                    <td style="text-align: center; color: #777;">{{ $index + 1 }}</td>
                    <td style="font-weight: bold; color: #333;">{{ $patologia->nombre }}</td>
                    <td style="color: #555;">
                        @if($patologia->observaciones)
                            {{ $patologia->observaciones }}
                        @else
                            <em style="color: #999;">Sin observaciones registradas</em>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div style="border: 1px dashed #ccc; padding: 15px; text-align: center; color: #777; margin-top: 10px; background-color: #fafafa;">
            El paciente no presenta antecedentes patológicos registrados en el sistema.
        </div>
    @endif

    <div style="margin-top: 60px;">
        <table width="100%">
            <tr>
                <td width="30%"></td> <td width="40%" style="border-top: 1px solid #000; text-align: center; padding-top: 5px;">
                    <span style="font-weight: bold; font-size: 10px;">{{ $historia->medico }}</span><br>
                    <span style="font-size: 9px; color: #555;">Firma y Sello del Médico Tratante</span>
                </td>
                <td width="30%"></td> </tr>
        </table>
    </div>

</body>
</html>
