<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket Venta #{{ $venta->id }}</title>
    <style>
        body { 
            font-family: 'DejaVu Sans', sans-serif; 
            font-size: 14px; 
            margin: 0; 
            padding: 1mm; 
        }
        .ticket { 
            width: 60mm; 
            margin: 0 auto; 
        }
        .header { 
            text-align: center; 
            margin-bottom: 3px; 
        }
        .logo { 
            width: 50mm; 
            height: 45mm;
            margin-bottom: 0.5rem; 
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .divider { 
            border-top: 1px dashed #000; 
            margin: 3px 0; 
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            font-size: 12px;
        }
        th, td { 
            padding: 1px 2px; 
            border-bottom: 1px dashed #eee;
        }
        .product-name { 
            max-width: 25mm; 
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .footer {
            margin-top: 5px;
            font-size: 9px;
        }
        .payment-details {
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="ticket">
        <!-- Encabezado con logo dinámico -->
        <div class="header">
            @if(file_exists($config['logo']))
                <img src="{{ $config['logo'] }}" class=logo alt="Logo">
            @endif
            <div class="bold" style="font-size: 11px; text-transform: uppercase;">{{ $sucursal->nombre }}</div>
            <div>{{ $config['direccion'] }}</div>
            <div>Aguascalientes, Aguascalientes</div>
        </div>

        <div class="divider"></div>

        <!-- Información de la venta -->
        <div>
            <div><span class="bold">Ticket:</span> #{{ $venta->id }}</div>
            <div><span class="bold">Fecha:</span> {{ $venta->created_at->format('d/m/Y H:i:s') }}</div>
            <div><span class="bold">Cliente:</span> {{ $venta->cliente ? $venta->cliente->nombre : 'Sin Cambio' }}</div>
        </div>

        <div class="divider"></div>

        <!-- Productos -->
        <table>
            <tr class="bold">
                <th class="product-name">Producto</th>
                <th width="12%">Cant</th>
                <th width="23%">P.U.</th>
                <th width="25%">Total</th>
            </tr>
            @foreach($venta->detalles as $detalle)
            <tr>
                <td class="product-name">{{ $detalle->producto->nombre }}</td>
                <td class="text-center">{{ $detalle->cantidad }}</td>
                <td class="text-right">${{ number_format($detalle->precio_unitario, 2) }}</td>
                <td class="text-right">${{ number_format($detalle->total_linea, 2) }}</td>
            </tr>
            @endforeach
        </table>

        <div class="divider"></div>

        <!-- Totales -->
        <div class="text-right bold" style="font-size: 11px;">
            TOTAL GENERAL: ${{ number_format($venta->total, 2) }}
        </div>

        <div class="divider"></div>

        <!-- Métodos de pago -->
        <div class="payment-details">
            <div class="bold">DETALLES DE PAGO</div>
            
            @if($venta->metodo_pago === 'multipago')
                @foreach($venta->pagos as $pago)
                <div>
                    {{ ucfirst($pago->metodo_pago) }}: ${{ number_format($pago->monto, 2) }}
                    @if($pago->metodo_pago === 'transferencia' && $pago->destinatario_transferencia)
                        (a {{ $pago->destinatario_transferencia }})
                    @endif
                </div>
                @endforeach
            @else
                <div>
                    {{ ucfirst($venta->metodo_pago) }}: ${{ number_format($venta->total, 2) }}
                    @if($venta->metodo_pago === 'transferencia' && $venta->pagos->first()->destinatario_transferencia)
                        (a {{ $venta->pagos->first()->destinatario_transferencia }})
                    @endif
                </div>
            @endif
        </div>

        <div class="divider"></div>

        <!-- QR y información adicional -->
        <div class="footer text-center">
            <div>Únete a nuestro grupo de WhatsApp</div>
            <div>y descubre nuestros nuevos modelos cada semana.</div>
            
            @if(file_exists($config['qr_whatsapp']))
            <div style="margin: 3px 0;">
                <img src="{{ $config['qr_whatsapp'] }}" style="width: 35mm; height: 32mm;">
            </div>
            @endif
            
            <div class="bold">¡Gracias por tu compra!</div>
            <div>Visítanos nuevamente.</div>
            <div>{{ date('d/m/Y H:i') }}</div>
        </div>
    </div>
</body>
</html>