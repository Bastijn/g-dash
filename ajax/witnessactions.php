<?php
session_start();
if($_SESSION['G-DASH-loggedin']==TRUE) {
include('../lib/functions/functions.php');
include('../config/config.php');
require_once('../lib/EasyGulden/easygulden.php');
$gulden = new Gulden($CONFIG['rpcuser'],$CONFIG['rpcpass'],$CONFIG['rpchost'],$CONFIG['rpcport']);

$guldenD = "GuldenD";
$guldenCPU = GetProgCpuUsage($guldenD);
$guldenMEM = GetProgMemUsage($guldenD);

$returnarray = array();

if($guldenCPU > 0 && $guldenMEM > 0) {
if(isset($_GET['action'])) {
	
	//Create a new witness account
	if($_GET['action']=="createaccount") {
		$addaccountpassword = $_POST['addpassword'];
		//Check the passphrase and unlock the wallet for 10 seconds if password is not empty
		$guldenresponse = "0";
		if($addaccountpassword!="") {
			$gulden->walletpassphrase($addaccountpassword, 10);
			$guldenresponse = $gulden->response['error']['code'];
			$guldenresponsemessage = $gulden->response['error']['message'];
			
			if($guldenresponse!="-14") {
				if(strpos($_POST['accountname'], "*")===false) {
					$accountnamechars = $_POST['accountname'];
					$accountnamechars = str_replace(".", "_",$accountnamechars);
					$createaccount = $gulden->createwitnessaccount($accountnamechars);
					if($createaccount == false) {
						$returnarray['code'] = $gulden->response['error']['code'];
						$returnarray['message'] = $gulden->response['error']['message'];
					} else {
						$returnarray = $createaccount;
					}
				}
			} else {
				$returnarray['code'] = $guldenresponse;
				$returnarray['message'] = $guldenresponsemessage;
			}
		} else {
			$returnarray['code'] = "0";
			$returnarray['message'] = "No wallet password supplied.";
		}
		
		echo json_encode($returnarray);
	}

	//Withdraw initial funding of a witness account
	elseif($_GET['action']=="withdrawif") {
		if(isset($_POST['fromlabel'])!="" && isset($_POST['tolabel'])!="" && isset($_POST['pass'])!="") {
			$fromaccount = trim($_POST['fromlabel']);
			$sendtoaccount = trim($_POST['tolabel']);
			$sendtopass = trim($_POST['pass']);
			$amounttowithdraw = -1;
			
			//Get the locked balance from the witness account
			$totalbalance = $gulden->getbalance($fromaccount, 0);
			
			//Check if there is any balance on the witness account
			if($totalbalance <= 0) {
				$returnarray = "-6";
			} else {
				//Check the passphrase and unlock the wallet for 10 seconds if password is not empty
				$guldenresponse = "0";
				if($sendtopass!="") {
					$gulden->walletpassphrase($sendtopass, 10);
					$guldenresponse = $gulden->response['error']['code'];
					$guldenresponsemessage = $gulden->response['error']['message'];
				}
				
				//Send funds from witness account to specified wallet account
                $gulden->move($fromaccount, $sendtoaccount, $amounttowithdraw);
				$witnessresponse = $gulden->response['error']['code'];
				$witnessresponsemessage = $gulden->response['error']['message'];
				
				if($guldenresponse!="-14") {
					if($guldenresponse!="-4") {
						if($witnessresponse) {
							if($witnessresponse == "-4") {
								$returnarray = "-6";
							} else {
								$returnarray = $guldenresponse." - ".$guldenresponsemessage."; ".$witnessresponse. " - ".$witnessresponsemessage;
							}
						} else {
							$returnarray = "1";
						}
					} else {
						//Signing transaction failed
						$returnarray = "-4";
					}
				} else {
					//Passphrase incorrect
					$returnarray = "-1";
				}
			}
			
			echo json_encode($returnarray);
		}
	}
	
	//Fund a witness account
	elseif($_GET['action']=="fundaccount") {
		if(isset($_POST['fromlabel'])!="" && isset($_POST['amount'])!="" && isset($_POST['pass'])!="" && isset($_POST['tolabel'])!="" && isset($_POST['locktime'])!="") {
			$sendtoaccount = trim($_POST['tolabel']);
			$sendtopass = trim($_POST['pass']);
			$sendamount = trim($_POST['amount']);
			$fromaccount = trim($_POST['fromlabel']);
			$locktime = trim($_POST['locktime'])."m";
			
			//Check the confirmed, unconfirmed and locked balance of this witness account. If this is 
			//more than zero the account is already funded.
			$confirmedbalance = $gulden->getbalance($witnessname, 0);
			$unconfirmedbalance = $gulden->getimmaturebalance($witnessname);
			$lockedbalance = $gulden->getlockedbalance($witnessname);
			
			$totalbalance = $confirmedbalance + $unconfirmedbalance + $lockedbalance;
			
			//If the total balance as above is more than zero, it's already funded.
			//This should also be captured now by GuldenD and return an error.
			if($totalbalance > 0) {
				$returnarray = "-8";
			} else {
				//Check the passphrase and unlock the wallet for 10 seconds if password is not empty
				$guldenresponse = "0";
				if($sendtopass!="") {
					$gulden->walletpassphrase($sendtopass, 10);
					$guldenresponse = $gulden->response['error']['code'];
					$guldenresponsemessage = $gulden->response['error']['message'];
				}
				
				//Send funds to witness account
				$gulden->fundwitnessaccount($fromaccount, $sendtoaccount, $sendamount, $locktime);
				$witnessresponse = $gulden->response['error']['code'];
				$witnessresponsemessage = $gulden->response['error']['message'];
				
				if($guldenresponse!="-14") {
					if($guldenresponse!="-4") {
						if($witnessresponse) {
							if($witnessresponse == "-1") {
								$returnarray = "-7";
							} elseif($witnessresponse == "-4") {
								$returnarray = "-6";
							} else {
								$returnarray = $guldenresponse." - ".$guldenresponsemessage."; ".$witnessresponse. " - ".$witnessresponsemessage;
							}
						} else {
							$returnarray = "1";
						}
					} else {
						//Signing transaction failed
						$returnarray = "-4";
					}
				} else {
					//Passphrase incorrect
					$returnarray = "-1";
				}
			}
			
			echo json_encode($returnarray);
		}
	}
	
	//Import witness account
	elseif($_GET['action']=="importaccount") {
		if(strpos($_POST['importaccountname'], "*")===false && $_POST['importaccountkey']!="") {
			$importpass = $_POST['importpass'];
			
			//Check the passphrase and unlock the wallet for 10 seconds if password is not empty
			$guldenresponse = "0";
			if($importpass!="") {
				$gulden->walletpassphrase($importpass, 10);
				$guldenresponse = $gulden->response['error']['code'];
				$guldenresponsemessage = $gulden->response['error']['message'];
				
				if($guldenresponse!="-14") {
					$accountnamechars = $_POST['importaccountname'];
					$accountnamechars = str_replace(".", "_",$accountnamechars);
					$importaccount = $gulden->importwitnesskeys($accountnamechars, $_POST['importaccountkey'], true);
					if($importaccount == false) {
						$returnarray['code'] = $gulden->response['error']['code'];
						$returnarray['message'] = $gulden->response['error']['message'];
					} else {
						$returnarray = $importaccount;
					}
				} else {
					$returnarray['code'] = $guldenresponse;
					$returnarray['message'] = $guldenresponsemessage;
				}
				
			} else {
				$returnarray['code'] = "0";
				$returnarray['message'] = "No wallet password supplied.";
			}
			
			echo json_encode($returnarray);
		}
	}
	
	//Withdraw earnings from a witness account
	elseif($_GET['action']=="withdrawearnings") {
		if(isset($_POST['fromlabel'])!="" && isset($_POST['toaddress'])!="") {
			$sendtoaddress = trim($_POST['toaddress']);
			$sendtopass = trim($_POST['pass']);
			$fromaccount = trim($_POST['fromlabel']);
			
			//Get the balance from the witness account that can be withdrawn
			$currentbalance = $gulden->getbalance($fromaccount);
			
			//TODO: Can be removed when fixed, and original call can be used again
			//Temp solution as the getlockedbalance RPC command is broken in Gulden 2.0.0.5
			//Get the witness accounts that belong to this wallet
			$mywitnessaccountsnetwork = $gulden->getwitnessinfo("tip", true, true);
			
			//Only get the witness address list of the witness accounts that belong to this wallet
			$mywitnessaddresslist = $mywitnessaccountsnetwork[0]['witness_address_list'];
			
			//Get the witness account data from the list of all witness accounts
			$witnessdata = selectElementWithValue($mywitnessaddresslist, "ismine_accountname", $fromaccount);
			
			//Change multidimensional array to single array
			$witnessdata = $witnessdata[0];
			
			//Get the amount of NLG locked
			$lockedbalance = $witnessdata['amount'];
			
			//TODO: When fixed, remove from previous todo until here and uncomment below
			//Get the amount that was locked for this account
			//$lockedbalance = $gulden->getlockedbalance($fromaccount);
			
			//Substract the locked balance from the current balance, resulting in a value that can be withdrawn
			$sendamount = $currentbalance - $lockedbalance;
			
			//Check the passphrase and unlock the wallet for 10 seconds if password is not empty
			$guldenresponse = "1";
			if($sendtopass!="") {
				$gulden->walletpassphrase($sendtopass, 10);
				$guldenresponse = $gulden->response['error']['code'];
				$guldenresponsemessage = $gulden->response['error']['message'];
			}
			
			//Validate an address before taking any action
			$validation = $gulden->validateaddress($sendtoaddress);
			$guldenresponse = $gulden->response;
			$validaddress = $guldenresponse['result']['isvalid'];
			
			if($validaddress=="true") {
				//Send earnings to external address, auto-substract the fee
				$gulden->sendtoaddressfromaccount($fromaccount, $sendtoaddress, $sendamount, "", "", true);
				$witnessresponse = $gulden->response['error']['code'];
				$witnessresponsemessage = $gulden->response['error']['message'];
				
				if($guldenresponse!="-14") {
					if($guldenresponse!="-4") {
						if($witnessresponse) {
							if($witnessresponse == "-1") {
								$returnarray = "-7";
							} elseif($witnessresponse == "-13") {
								$returnarray = "-8";
							} else {
								$returnarray = $guldenresponse." - ".$guldenresponsemessage."; ".$witnessresponse. " - ".$witnessresponsemessage." (amount: ".$sendamount.")";
							}
						} else {
							$returnarray = "1";
						}
					} else {
						//Signing transaction failed
						$returnarray = "-4";
					}
				} else {
					//Passphrase incorrect
					$returnarray = "-1";
				}
			} else {
				$returnarray = "-2";
			}
			
			echo json_encode($returnarray);
		}
	}
	
	//Export a witness key
	elseif($_GET['action']=="exportkey") {
		$fromaccount = trim($_POST['selectedaccount']);
		$exportpass = trim($_POST['pass']);
		
		//Check the passphrase and unlock the wallet for 10 seconds if password is not empty
		$guldenresponse = "0";
		if($exportpass!="") {
			$gulden->walletpassphrase($exportpass, 10);
			$guldenresponse = $gulden->response['error']['code'];
			$guldenresponsemessage = $gulden->response['error']['message'];
			
			if($guldenresponse!="-14") {
				if(strpos($fromaccount, "*")===false) {
					$exportkey = $gulden->getwitnessaccountkeys($fromaccount);
					if($exportkey == false) {
						$returnarray = "<div class='alert alert-warning'>".$gulden->response['error']['message']."</div>";
					} else {
						$returnarray = "<div class='alert alert-success'>
										<input id='witkey' name='witkey' type='text' class='form-control' readonly value='".$exportkey."'>
										</div>";
					}
				}
			} else {
				$returnarray = "<div class='alert alert-warning'>".$guldenresponsemessage."</div>";
			}
		} else {
			$returnarray = "<div class='alert alert-warning'>No wallet password supplied.</div>";
		}
		
		echo json_encode($returnarray);
	}

	//Change account name
	elseif($_GET['action']=="changeacc") {
		if(isset($_POST['changedacc'])!="" && isset($_POST['currentacc'])!="") {
			if(strpos($_POST['changedacc'], "*")===false) {
				$accountnamechars = $_POST['changedacc'];
				$accountnamechars = str_replace(".", "_",$accountnamechars);
				$chaccount = $gulden->changeaccountname($_POST['currentacc'], trim($accountnamechars));
				if($chaccount == "false") {
					$returnarray = $gulden->response;
				} else {
					$returnarray = $chaccount;
				}
				
				echo json_encode($returnarray);
			}
		}
	}
	
	//Delete a witness account
	elseif($_GET['action']=="deleteaccount") {
		$fromaccount = trim($_POST['selectedaccount']);
		$deletepass = trim($_POST['pass']);
		
		//Check the passphrase and unlock the wallet for 10 seconds if password is not empty
		$guldenresponse = "0";
		if($deletepass!="") {
			$gulden->walletpassphrase($deletepass, 10);
			$guldenresponse = $gulden->response['error']['code'];
			$guldenresponsemessage = $gulden->response['error']['message'];
			
			if($guldenresponse!="-14") {
				if(strpos($fromaccount, "*")===false) {
					$deletedaccount = $gulden->deleteaccount($fromaccount);
					if($deletedaccount == false) {
						$returnarray = "<div class='alert alert-warning'>".$gulden->response['error']['message']."</div>";
					} else {
						$returnarray = "<div class='alert alert-success'>
										Account has been deleted.
										</div>";
					}
				}
			} else {
				$returnarray = "<div class='alert alert-warning'>".$guldenresponsemessage."</div>";
			}
		} else {
			$returnarray = "<div class='alert alert-warning'>No wallet password supplied.</div>";
		}
		
		echo json_encode($returnarray);
	}
	
}
}
}
session_write_close();
?>
