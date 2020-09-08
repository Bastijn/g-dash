<?php $GDASH = array (
  'currentversion' => '1.4',
  'updatecheck' => 'https://api.github.com/repos/Bastijn/g-dash/releases/latest',
  'updatelocation' => 'https://github.com/Bastijn/g-dash/archive/',
  'updateau' => 'https://g-dash.nl/autoupdate.php',
  'nlgrate' => 
	  array (
	    '0' => array (
		    'exchange' => 'BitTrex',
		    'market' => 'https://bittrex.com/api/v1.1/public/getticker?market=BTC-NLG',
		    'link' => 'result->Bid',
		    'symbol' => 'BTC',
		    'rounding' => 6,
		  ),
	  ),
); ?>
