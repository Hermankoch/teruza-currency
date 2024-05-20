<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Currency App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>
<body>
<nav class="navbar bg-body-tertiary">
    <div class="container">
        <span>
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                 class="bi bi-currency-dollar" viewBox="0 0 16 16">
                <path
                    d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73z"/>
            </svg>
        Herman's Currency App
        </span>
    </div>
</nav>

<div class="d-flex justify-content-center py-1">
    <button id="loadRates" class="btn btn-primary">
        Load Rates
    </button>
</div>

<div class="d-flex justify-content-center py-1">

    <span class="input-group-text" id="basic-addon1">R</span>
    <input type="text" id="amount" class="form-control-sm" placeholder="134.20">
    <select id="currency" class=""></select>
    <button id="convert" class="btn btn-secondary mx-2">
        Convert
    </button>

</div>

<div class="d-flex justify-content-center py-1">

    <p id="conversionResult"></p>

</div>

<div class="container">
    <table id="ratesTable" class="table"></table>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"
        integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    let currencies = [];
    $(document).ready(function () {
        $('#loadRates').on('click', function () {
                $.ajax({
                        url: '{{ route('getRates') }}',
                        method: 'GET',
                        success: function (data) {
                            if (data.rates['errors'])
                            {
                                let error = '';
                                $.each(data.rates['errors'], function (code, e) {
                                    error += e;
                                });
                                alert(error);
                            }
                            let currencyOptions = [];
                            let rates = data.rates.forex;
                            let table = '<tr><th>Currency</th><th>Rate</th></tr>';
                            $.each(rates, function (currency, rate) {
                                table += `<tr><td>${currency}</td><td>${rate}</td></tr>`;
                                let pair1 = currency.split('_')[0];
                                let pair2 = currency.split('_')[1];
                                if (pair1 !== 'ZAR') {
                                    currencyOptions.push(pair1)
                                }
                                if (pair2 !== 'ZAR') {
                                    currencyOptions.push(pair2)
                                }
                            });

                            $('#ratesTable').html(table);

                            let options = '';
                            currencyOptions = removeDuplicates(currencyOptions);
                            currencyOptions.forEach(function (currency) {
                                options += `<option value="${currency}">${currency}</option>`;
                            });
                            $('#currency').html(options);
                        }
                    }
                )
            }
        );

        // Convert function
        $('#convert').click(function () {
                let amount = parseFloat($('#amount').val());
                let currency = $('#currency').val();
                if (isNaN(amount) || amount <= 0) {
                    alert('Please enter a valid amount.');
                    return;
                }
                if (currency == '' || currency == null)
                {
                    alert('You have to load the rates first to get all currencies.');
                    return;
                }

                $.ajax({
                    url: '{{ route('getRates') }}',
                    method: 'GET',
                    success: function (data) {
                        if (data.rates['errors'])
                        {
                            let error = '';
                            $.each(data.rates['errors'], function (code, e) {
                                error += e;
                            });
                            alert(error);
                        }
                        let rates = data.rates.forex;
                        let rateKey = `ZAR_${currency}`;
                        let inverseRateKey = `${currency}_ZAR`;

                        let rate = rates[rateKey] ? parseFloat(rates[rateKey]) : (1 / parseFloat(rates[inverseRateKey]));
                        if (!rate) {
                            $('#conversionResult').text('Rate not found for selected currency.');
                            return;
                        }
                        let convertedAmount = rate * amount;
                        $('#conversionResult').text(`Converted Amount: ${convertedAmount.toFixed(2)} ZAR`);
                    }
                });
            }
        );
    });

    // To remove duplicate array values ES6 notation
    function removeDuplicates(arr) {
        return [...new Set(arr)];
    }
</script>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
        crossorigin="anonymous"></script>
</body>
</html>
