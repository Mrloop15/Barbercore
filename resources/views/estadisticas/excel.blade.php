@php echo '<?xml version="1.0" encoding="UTF-8"?>'; @endphp
@php echo '<?mso-application progid="Excel.Sheet"?>'; @endphp
<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet" xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">
<Styles>
 <Style ss:ID="Default"><Alignment ss:Vertical="Center"/><Font ss:FontName="Arial" ss:Size="10"/></Style>
 <Style ss:ID="Title"><Font ss:Bold="1" ss:Size="18" ss:Color="#1C1C1C"/></Style>
 <Style ss:ID="Brand"><Font ss:Bold="1" ss:Color="#C9A227"/></Style>
 <Style ss:ID="Header"><Font ss:Bold="1" ss:Color="#FFFFFF"/><Interior ss:Color="#1C1C1C" ss:Pattern="Solid"/><Alignment ss:Horizontal="Center"/></Style>
 <Style ss:ID="Label"><Font ss:Bold="1" ss:Color="#6B6B6B"/><Interior ss:Color="#FAF8F2" ss:Pattern="Solid"/></Style>
 <Style ss:ID="Cell"><Borders><Border ss:Position="Bottom" ss:LineStyle="Continuous" ss:Weight="1" ss:Color="#E5E0D6"/></Borders></Style>
 <Style ss:ID="Money" ss:Parent="Cell"><NumberFormat ss:Format="&quot;$&quot;#,##0.00"/></Style>
</Styles>
<Worksheet ss:Name="Resumen"><Table>
 <Column ss:Width="170"/><Column ss:Width="130"/><Column ss:Width="130"/><Column ss:Width="130"/><Column ss:Width="150"/>
 <Row ss:Height="28"><Cell ss:StyleID="Title" ss:MergeAcross="4"><Data ss:Type="String">Reporte BarberCore</Data></Cell></Row>
 <Row><Cell ss:StyleID="Brand" ss:MergeAcross="4"><Data ss:Type="String">{{ $barberia }}</Data></Cell></Row>
 <Row><Cell ss:StyleID="Label"><Data ss:Type="String">Periodo</Data></Cell><Cell ss:MergeAcross="3"><Data ss:Type="String">{{ $desde->format('d/m/Y') }} al {{ $hasta->format('d/m/Y') }}</Data></Cell></Row>
 <Row><Cell ss:StyleID="Label"><Data ss:Type="String">Filtro de estado</Data></Cell><Cell ss:MergeAcross="3"><Data ss:Type="String">{{ ucfirst($estado) }}</Data></Cell></Row>
 <Row/>
 <Row><Cell ss:StyleID="Header"><Data ss:Type="String">Total de citas</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Completadas</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Pendientes</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Canceladas</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Ingresos confirmados</Data></Cell></Row>
 <Row><Cell ss:StyleID="Cell"><Data ss:Type="Number">{{ $totalCitas }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="Number">{{ $completadas }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="Number">{{ $pendientes }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="Number">{{ $canceladas }}</Data></Cell><Cell ss:StyleID="Money"><Data ss:Type="Number">{{ (float) $ingresos }}</Data></Cell></Row>
</Table></Worksheet>
<Worksheet ss:Name="Detalle de citas"><Table>
 <Column ss:Width="80"/><Column ss:Width="70"/><Column ss:Width="70"/><Column ss:Width="160"/><Column ss:Width="150"/><Column ss:Width="130"/><Column ss:Width="90"/><Column ss:Width="90"/><Column ss:Width="220"/>
 <Row><Cell ss:StyleID="Header"><Data ss:Type="String">Fecha</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Hora inicio</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Hora fin</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Cliente</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Servicio</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Barbero</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Estado</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Importe</Data></Cell><Cell ss:StyleID="Header"><Data ss:Type="String">Observaciones</Data></Cell></Row>
 @foreach($citas as $cita)
 <Row><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ \Carbon\Carbon::parse($cita->hora_inicio)->format('H:i') }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ \Carbon\Carbon::parse($cita->hora_fin)->format('H:i') }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ trim(($cita->cliente->nombre ?? 'Sin cliente').' '.($cita->cliente->apellido ?? '')) }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $cita->servicio->nombre ?? 'Sin servicio' }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $cita->barbero->nombre ?? 'Sin asignar' }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ ucfirst($cita->estado) }}</Data></Cell><Cell ss:StyleID="Money"><Data ss:Type="Number">{{ (float) $cita->precio }}</Data></Cell><Cell ss:StyleID="Cell"><Data ss:Type="String">{{ $cita->observaciones ?? '' }}</Data></Cell></Row>
 @endforeach
</Table><WorksheetOptions xmlns="urn:schemas-microsoft-com:office:excel"><FreezePanes/><FrozenNoSplit/><SplitHorizontal>1</SplitHorizontal><TopRowBottomPane>1</TopRowBottomPane><ActivePane>2</ActivePane></WorksheetOptions></Worksheet>
</Workbook>
