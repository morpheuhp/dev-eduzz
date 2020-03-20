<?php
/*
 * Desenvolvido por Fernando Camacho
 * fernando@fmsistemas.net
 */

$sql = "select * from transacoes where user='{$_SESSION['usuario'][id]}'";
$sql .= " order by data,hora desc LIMIT 100";
$dados = $db->return_array($sql);

$saldo = $db->return_array_result("select * from saldo where id='{$_SESSION['usuario']['id']}'");
?>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/data.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<style>
    .highcharts-figure, .highcharts-data-table table {
        min-width: 360px; 
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #EBEBEB;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }
    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }
    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }
    .highcharts-data-table td, .highcharts-data-table th, .highcharts-data-table caption {
        padding: 0.5em;
    }
    .highcharts-data-table thead tr, .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }
    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    .ld-label {
        width:200px;
        display: inline-block;
    }

    .ld-url-input {
        width: 500px; 
    }

    .ld-time-input {
        width: 40px;
    }


</style>

<table>
    <tr>
        <td style="vertical-align: top">
            <table class="extrato">
                <tr>
                    <th colspan="5">ÚLTIMAS TRANSAÇÕES</th>
                </tr>
                <tr>
                    <th>Data</th>
                    <th>Depósito</th>
                    <th>Compra (BTC)</th>
                    <th>Venda (R$)</th>
                    <th>BTC</th>
                </tr>
                <?php
                foreach ($dados as $ind => $v) {

                    $compra = ($v['compra'] > 0) ? $v['compra'] / $v['btc'] : 0;
                    $venda = ($v['venda'] > 0) ? $v['venda'] * $v['btc'] : 0;
                    ?>
                    <tr>
                        <td class="numero"><?= $db->date_dbbr($v['data']); ?></td>
                        <td class="numero"><?= $db->n_View($v['deposito']); ?></td>
                        <td class="numero"><?= number_format($compra, 5, ',', '.'); ?></td>
                        <td class="numero"><?= number_format($venda, 5, ',', '.'); ?></td>
                        <td class="numero"><?= number_format($v['btc'], 5, ',', '.'); ?></td>
                    </tr>
                <?php } ?>
            </table>
        </td>

        <td style="vertical-align: top">

            <table class="extrato">
                <tr>
                    <td colspan='5' style='border:1px solid black;text-align: center'>Saldo Atual em <b>BTC</b> <?= $saldo[bitcoin]; ?> em <b>R$</b> <?= $saldo[valor]; ?></td>
                </tr>
                <tr>
                    <th >COMPRAR: </th>
                    <td style="width:300px">
                        <input class="numero" type="text" id="comprar" onkeyup="numberMask(this, 5)">
                        <input type='button' value='Utilizar Saldo' onclick="usaldo('comprar')">
                    </td>
                    <td>
                        <a href="javascript:transacao(1)" title='Comprar' style='cursor:pointer;font-size:22px;color:green'>&laquo;</a>
                    </td>
                </tr>
                <tr>
                    <th >VENDER: </th>
                    <td >
                        <input class='numero' type="text" id="vender" onkeyup="numberMask(this, 5)">
                        <input type='button' value='Utilizar Saldo' onclick="usaldo('vender')">
                    </td>
                    <td><a href="javascript:transacao(0)" title='Vender' style='cursor:pointer;font-size:22px;color:red'>&raquo;</a></td>
                </tr>
            </table>
        </td>
        <td style="vertical-align:top">
            <figure class="highcharts-figure">
                <div id="container"></div>    
                <div id="container2"></div>    
            </figure>

            <div  style="padding:15px;border:1px solid black;margin: 30px;text-align: center">
                <input type="checkbox" checked="checked" id="enablePolling"/>
                Habilitar Ao Vivo
                &nbsp; &nbsp;&nbsp; &nbsp;&nbsp; &nbsp;


                Tempo de Transição (Segundos):

                <input class="ld-time-input" type="number" value="10" id="pollingTime"/>

                <br><br>
                URL dos dados:

                <input class="ld-url-input" type="text" id="fetchURL"/>
                
            </div>
        </td>

    </tr>
</table>


<script>
    $(document).ready(function () {
        transacao = function (_type) {
            if (_type == 1) {
                _valor = $('#comprar').val();
            } else {
                _valor = $('#vender').val();
            }

            _a = ajaxValue('ajax.php?mode=<?= fm('confirmaTransacao'); ?>&type=' + _type + '&valor=' + _valor);
            location.reload();
        }
        usaldo = function (_type) {
            _v = ajaxValue('ajax.php?mode=<?= fm('ultimovalor'); ?>');

            if (_type == 'vender') {
                if (<?= ceil($saldo[bitcoin]); ?> > 0) {
                    $('#vender').val(<?= $saldo[bitcoin]; ?>);
                } else {
                    alert('Saldo Insuficiente');
                }
            } else {
                if (<?= ceil($saldo[valor]); ?> > 0) {
                    _vl =<?= $saldo[valor]; ?>;
                    $('#comprar').val(_vl.toFixed(5));
                } else {
                    alert('Saldo Insuficiente');
                }
            }
        }


        var defaultData = 'http://bitcoin.fmsistemas.online/time.csv';
        var urlInput = document.getElementById('fetchURL');
        
        var pollingCheckbox = document.getElementById('enablePolling');
        var pollingInput = document.getElementById('pollingTime');

        function createChart() {
            Highcharts.chart('container', {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: '<b>Dados ao Vivo<b/>'
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                        minAnnounceInterval: 15000,
                        announcementFormatter: function (allSeries, newSeries, newPoint) {
                            if (newPoint) {
                                return 'New point added. Value: ' + newPoint.y;
                            }
                            return false;
                        }
                    }
                },
                data: {
                    csvURL: urlInput.value,
                    enablePolling: pollingCheckbox.checked === true,
                    dataRefreshRate: parseInt(pollingInput.value, 10)
                }
            });

            if (pollingInput.value < 1 || !pollingInput.value) {
                pollingInput.value = 1;
            }
        }


        function createChart2() {
            Highcharts.chart('container2', {
                chart: {
                    type: 'spline'
                },
                title: {
                    text: 'Live Data'
                },
                accessibility: {
                    announceNewData: {
                        enabled: true,
                        minAnnounceInterval: 300000,
                        announcementFormatter: function (allSeries, newSeries, newPoint) {
                            if (newPoint) {
                                return 'New point added. Value: ' + newPoint.y;
                            }
                            return false;
                        }
                    }
                },
                data: {
                    csvURL: 'http://bitcoin.fmsistemas.online/time.php?dados=1',
                    dataRefreshRate: parseInt(100, 10)
                }
            });            
        }
        urlInput.value = defaultData;

        pollingCheckbox.onchange = urlInput.onchange = pollingInput.onchange = createChart;

        createChart();
        createChart2();
    });
</script>