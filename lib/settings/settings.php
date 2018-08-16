<?php $GDASH = array (
  'currentversion' => '1.03',
  'updatecheck' => 'https://g-dash.nl/autoupdate.php',
  'updatelocation' => 'https://g-dash.nl/download/',
  'nlgrate' => 
	  array (
	    '0' => array (
		    'exchange' => 'Nocks',
		    'market' => 'https://api.nocks.com/api/v2/trade-market/NLG-EUR',
		    'link' => 'data->buy->amount',
		    'symbol' => '&euro;',
		    'rounding' => 2,
		  ),
	    '1' => array (
		    'exchange' => 'GuldenTrader',
		    'market' => 'https://guldentrader.com/api/ticker',
		    'link' => 'buy',
		    'symbol' => '&euro;',
		    'rounding' => 2,
		  ),
	    '2' => array (
			'exchange' => 'CryptoCompare',
			'market' => 'https://min-api.cryptocompare.com/data/price?fsym=NLG&tsyms=USD',
			'link' => 'USD',
			'symbol' => '&dollar;',
			'rounding' => 2,
	      ),
	    '3' => array (
		    'exchange' => 'BitTrex',
		    'market' => 'https://bittrex.com/api/v1.1/public/getticker?market=BTC-NLG',
		    'link' => 'result->Bid',
		    'symbol' => 'BTC',
		    'rounding' => 6,
		  ),
	  ),
); ?>
