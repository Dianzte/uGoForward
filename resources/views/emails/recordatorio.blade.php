<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Recordatorio de Evento — uGoForward</title>
</head>
<body style="margin:0; padding:0; font-family:'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif; background-color:#0f0e1a; color:#e2e8f0;">

    {{-- Wrapper principal --}}
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
           style="background-color:#0f0e1a; padding: 40px 16px;">
        <tr>
            <td align="center">

                {{-- Contenedor del email (max 600px) --}}
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                       style="max-width:600px; width:100%;">

                    {{-- ===== HEADER ===== --}}
                    <tr>
                        <td style="background: linear-gradient(135deg, #6d28d9 0%, #4f46e5 50%, #06b6d4 100%);
                                   border-radius: 16px 16px 0 0;
                                   padding: 36px 40px 32px;
                                   text-align: center;">

                            {{-- Ícono de reloj --}}
                            <div style="display:inline-block; background:rgba(255,255,255,0.15);
                                        border-radius:50%; width:64px; height:64px;
                                        line-height:64px; font-size:32px; margin-bottom:16px;">
                                ⏰
                            </div>

                            <h1 style="margin:0; font-size:26px; font-weight:700;
                                       color:#ffffff; letter-spacing:-0.5px;">
                                ¡Tienes un evento próximo!
                            </h1>
                            <p style="margin:8px 0 0; font-size:14px; color:rgba(255,255,255,0.75);">
                                Este es tu recordatorio de <strong>uGoForward</strong>
                            </p>
                        </td>
                    </tr>

                    {{-- ===== CUERPO PRINCIPAL ===== --}}
                    <tr>
                        <td style="background-color:#1e1b2e; padding: 40px 40px 32px;">

                            {{-- Título del evento --}}
                            <div style="margin-bottom:28px;">
                                <p style="margin:0 0 6px; font-size:11px; font-weight:700;
                                          color:#a78bfa; text-transform:uppercase; letter-spacing:1.5px;">
                                    Evento
                                </p>
                                <h2 style="margin:0; font-size:24px; font-weight:700; color:#f1f5f9;
                                           line-height:1.3;">
                                    {{ $tarea->titulo }}
                                </h2>
                            </div>

                            {{-- Divider --}}
                            <div style="height:1px; background:linear-gradient(90deg, #4f46e5, transparent);
                                        margin-bottom:28px;"></div>

                            {{-- Fecha y hora --}}
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0"
                                   style="margin-bottom:28px;">
                                <tr>
                                    {{-- Fecha --}}
                                    <td width="48%" style="vertical-align:top;">
                                        <div style="background:#2d2a45; border-radius:12px; padding:18px 20px;
                                                    border-left:3px solid #6d28d9;">
                                            <p style="margin:0 0 4px; font-size:11px; font-weight:700;
                                                      color:#a78bfa; text-transform:uppercase; letter-spacing:1px;">
                                                📅 Fecha
                                            </p>
                                            <p style="margin:0; font-size:18px; font-weight:600; color:#f1f5f9;">
                                                {{ \Carbon\Carbon::parse($tarea->fecha)->format('d/m/Y') }}
                                            </p>
                                        </div>
                                    </td>

                                    <td width="4%"></td>

                                    {{-- Hora --}}
                                    <td width="48%" style="vertical-align:top;">
                                        <div style="background:#2d2a45; border-radius:12px; padding:18px 20px;
                                                    border-left:3px solid #06b6d4;">
                                            <p style="margin:0 0 4px; font-size:11px; font-weight:700;
                                                      color:#67e8f9; text-transform:uppercase; letter-spacing:1px;">
                                                🕐 Hora
                                            </p>
                                            <p style="margin:0; font-size:18px; font-weight:600; color:#f1f5f9;">
                                                @if($tarea->hora_evento)
                                                    {{ \Carbon\Carbon::parse($tarea->hora_evento)->format('H:i') }}
                                                @else
                                                    Todo el día
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            </table>

                            {{-- Recordatorio en X minutos --}}
                            <div style="background:linear-gradient(135deg, rgba(109,40,217,0.2), rgba(6,182,212,0.2));
                                        border:1px solid rgba(109,40,217,0.4);
                                        border-radius:12px; padding:16px 20px; margin-bottom:28px;
                                        text-align:center;">
                                <p style="margin:0; font-size:14px; color:#c4b5fd;">
                                    ⚡ Este recordatorio se envió
                                    <strong style="color:#a78bfa;">{{ $tarea->recordatorio_minutos }} minutos</strong>
                                    antes de tu evento
                                </p>
                            </div>

                            {{-- Descripción (si existe) --}}
                            @if(!empty($tarea->descripcion))
                            <div style="margin-bottom:28px;">
                                <p style="margin:0 0 10px; font-size:11px; font-weight:700;
                                          color:#a78bfa; text-transform:uppercase; letter-spacing:1.5px;">
                                    📝 Descripción
                                </p>
                                <div style="background:#2d2a45; border-radius:12px; padding:18px 20px;
                                            border-left:3px solid #4f46e5;">
                                    <p style="margin:0; font-size:15px; line-height:1.7; color:#cbd5e1;">
                                        {{ $tarea->descripcion }}
                                    </p>
                                </div>
                            </div>
                            @endif

                            {{-- CTA Button --}}
                            <div style="text-align:center; margin-top:8px;">
                                <a href="{{ config('app.url') }}/calendario"
                                   style="display:inline-block;
                                          background:linear-gradient(135deg, #6d28d9, #4f46e5);
                                          color:#ffffff; text-decoration:none;
                                          font-weight:700; font-size:15px;
                                          padding:14px 36px; border-radius:50px;
                                          letter-spacing:0.3px;
                                          box-shadow: 0 4px 15px rgba(109,40,217,0.4);">
                                    📅 Ver mi Calendario
                                </a>
                            </div>

                        </td>
                    </tr>

                    {{-- ===== FOOTER ===== --}}
                    <tr>
                        <td style="background-color:#16132a; border-radius:0 0 16px 16px;
                                   padding:24px 40px; text-align:center;
                                   border-top:1px solid rgba(255,255,255,0.05);">
                            <p style="margin:0 0 6px; font-size:13px; font-weight:700; color:#a78bfa;">
                                uGoForward
                            </p>
                            <p style="margin:0; font-size:12px; color:#64748b; line-height:1.6;">
                                Este correo fue generado automáticamente. Por favor no respondas a este mensaje.<br>
                                &copy; {{ date('Y') }} uGoForward. Todos los derechos reservados.
                            </p>
                        </td>
                    </tr>

                </table>
                {{-- /contenedor --}}

            </td>
        </tr>
    </table>

</body>
</html>
