var coinJs = {
    init: function () {
        // Select Referente a cotação
        $('#coin_from').on('change', function(){
            coinJs.checkBuild();
            $('.btn_days').removeClass('active');
        });
        $('#coin_to').on('change', function(){
            coinJs.checkBuild();
            $('.btn_days').removeClass('active');
        });

        // Botões referentes aos dias
        $('.btn_days').on('click', function (){
            $('.btn_days').removeClass('active');
            $(this).addClass('active');

            var dias = $(this).val();
            coinJs.checkBuildByPeriods(dias);
        });
    },
    checkBuild: function () {
        let coinFrom = $('#coin_from').val();
        let coinTo = $('#coin_to').val();

        $('.graphic_by_period').hide();

        if(coinFrom.length > 0 && coinTo.length > 0) {
           ajaxJs.getQuotation(coinFrom, coinTo);
        }
    },
    checkBuildByPeriods: function (days) {
        let coinFrom = $('#coin_from').val();
        let coinTo = $('#coin_to').val();

        if(coinFrom.length > 0 && coinTo.length > 0 && days > 0) {
            ajaxJs.getQuotatiosByPeriod(coinFrom, coinTo, days);
        }

    },
    createChart: function(dataArray) {
        var data = new google.visualization.DataTable();

        data.addColumn('string', 'Referência');
        data.addColumn('number', 'Cotação');
        data.addRows([
            ['Min', {v: parseFloat(dataArray['menorCotacao']), f: 'R$ '+funcoesAuxiliares.convertMoney(dataArray['menorCotacao'])}],
            ['Compra', {v: parseFloat(dataArray['compra']), f: 'R$ '+funcoesAuxiliares.convertMoney(dataArray['compra'])}],
            ['Max', {v: parseFloat(dataArray['maiorCotacao']), f: 'R$ '+funcoesAuxiliares.convertMoney(dataArray['maiorCotacao'])}]
        ]);

        var options = {
            title: 'Cotação '+dataArray['siglaFrom']+' - '+dataArray['siglaTo'],
            hAxis: {title: 'Referencia',  titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0},
            allowHtml: true,
            showRowNumber: true
        };

        var chart = new google.visualization.SteppedAreaChart(document.getElementById('chart_div'));

        chart.draw(data, options);
    },
    createChartByPeriod: function (dataArray, days) {
        var data = new google.visualization.DataTable();

        data.addColumn('date', 'Data');
        data.addColumn('number', 'Cotação');

        if (dataArray.length > 0) {
            let arrayRows = [];
            dataArray.forEach(function (item) {
                let convertData = funcoesAuxiliares.convertDataToUser(item['data']).split('/');

                console.log('converted Data',convertData);

                arrayRows.push(
                    [
                        {
                            v: new Date(convertData[1]+'-'+(convertData[0])+'-'+convertData[2]),
                            f: funcoesAuxiliares.convertDataToUser(item['data'])
                        },
                        {
                            v: parseFloat(item['compra']),
                            f: 'R$ '+funcoesAuxiliares.convertMoney(item['compra'])
                        }
                    ]
                );
            });

            console.log('dataarray', dataArray);
            console.log('arrayRows', arrayRows);
            data.addRows(arrayRows);
        } else {
            coinJs.messageAlert('danger',  'Não existe cotações suficientes para serem mostradas!');
            return false;
        }

        var options = {
            title: 'Cotação '+dataArray[0]['siglaFrom']+' - '+dataArray[0]['siglaTo']+' - '+days+' dias',
            hAxis: {title: 'Data', format: 'dd/MM', titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0, title: 'Cotação'},
            allowHtml: true,
            showRowNumber: true
        };

        var chart = new google.visualization.SteppedAreaChart(document.getElementById('cart_by_period'));

        chart.draw(data, options);
    },
    messageAlert: function (type, message, title) {
        coinJs.messageAlertRemove();
        var alert = '<div class="alert-'+type+'">'+message+'</div>';
        $('#alert_msg').append(alert);
    },
    messageAlertRemove: function () {
        $('#alert_msg').empty();
    }
};

var ajaxJs = {
    getQuotation: function (coin1, coin2) {
        let url = window.location.href;

        $.ajax({
            headers: {
                'X-CSRF-Token': $('#token').val()
            },
            type: 'POST',
            url: url+'currency-quote',
            dataType: "json",
            data: {
                'coinFrom': coin1,
                'coinTo': coin2
            },
            success: function (data) {
                var response = data.response;
                coinJs.messageAlertRemove();

                $('.cotacao').html("1 "+response.nomeFrom+" = ");
                $('.comparacao').html('R$ ' + funcoesAuxiliares.convertMoney(response.compra)+" "+response.nomeTo);

                $('.result_quotation').show();
                coinJs.createChart(response);
            },  // success
            error: function(jqXHR, textStatus, errorThrown) {
                var response = jqXHR.responseJSON;

                if(response.message) {
                    coinJs.messageAlert('danger', response.message);
                } else {
                    coinJs.messageAlert('danger', response.response[0]);
                }
            }
        }); // ajax
    },
    getQuotatiosByPeriod: function (coin1, coin2, days) {
        let url = window.location.href;

        $.ajax({
            headers: {
                'X-CSRF-Token': $('#token').val()
            },
            type: 'POST',
            url: url+'currency-quote-period',
            dataType: "json",
            data: {
                'coinFrom': coin1,
                'coinTo': coin2,
                'days': days
            },
            success: function (data) {
                var response = data.response;
                coinJs.messageAlertRemove();

                $('.graphic_by_period').show();
                coinJs.createChartByPeriod(response, days);
            },  // success
            error: function(jqXHR, textStatus, errorThrown) {
                var response = jqXHR.responseJSON;

                if(response.message) {
                    coinJs.messageAlert('danger', response.message);
                } else {
                    coinJs.messageAlert('danger', response.response[0]);
                    console.log('Error response',response.response[0]);
                }
            }
        }); // ajax
    }
}

var funcoesAuxiliares = {
    convertMoney: function (money) {
         let convertedMoney, splitMoney;

         splitMoney = (parseFloat(money).toFixed(2)).split('.');
         splitMoney[0] =  splitMoney[0].split(/(?=(?:...)*$)/).join('.');
         convertedMoney = splitMoney.join(',');

        return convertedMoney;
    },
    convertDataToUser: function (date) {
        let splitDatetime = date.split(' ');
        let splitData = splitDatetime[0].split('-')
        let humanDate = splitData[2]+'/'+splitData[1]+'/'+splitData[0];

        return humanDate;
    }
}

$(function(){
    coinJs.init();
});