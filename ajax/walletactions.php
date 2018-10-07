<?php
session_start();
if ($_SESSION['G-DASH-loggedin'] == true) {
    include('../config/config.php');
    include('../lib/functions/functions.php');
    require_once('../lib/EasyGulden/easygulden.php');
    $gulden = new Gulden($CONFIG['rpcuser'], $CONFIG['rpcpass'], $CONFIG['rpchost'], $CONFIG['rpcport']);

    $guldenD = "GuldenD";
    $guldenCPU = GetProgCpuUsage($guldenD);
    $guldenMEM = GetProgMemUsage($guldenD);

    $returnarray = array();

    if ($guldenCPU > 0 && $guldenMEM > 0) {
        if (isset($_GET['action'])) {
            //Create a wallet pass phrase for the first time
            if ($_GET['action'] == "createpass") {
                if (isset($_POST['passphrase']) != "") {
                    if (strpos($_POST['passphrase'], "*") === false) {
                        $createpassphrase = $gulden->encryptwallet($_POST['passphrase']);
                        if ($createpassphrase == "false") {
                            $returnarray = $gulden->response['error']['message'];
                        } else {
                            $returnarray = $createpassphrase;
                        }
                        echo json_encode($returnarray);
                    }
                } else {
                    echo json_encode("Passphrase is empty");
                }
            } //Create a new address for a specific account
            elseif ($_GET['action'] == "newaddress") {
                /*
                if(isset($_POST['account'])!="") {
                    $gulden->getnewaddress($_POST['account']);
                    $addresslist = $gulden->getaddressesbyaccount($_POST['account']);
                    $latestaddress = $addresslist[(count($addresslist)-1)];
                    $returnarray = $latestaddress;

                    echo json_encode($returnarray);
                }
                 *
                 */
            } //Change the wallet pass phrase
            elseif ($_GET['action'] == "newpass") {
                if (isset($_POST['oldp']) != "" && isset($_POST['newp']) != "") {
                    $changepass = $gulden->walletpassphrasechange($_POST['oldp'], $_POST['newp']);
                    if ($gulden->response['error'] != null) {
                        $returnarray = $gulden->response['error']['message'];
                    } else {
                        $returnarray = "Success";
                    }
                    echo json_encode($returnarray);
                }
            } //Show the Recovery Phrase for this wallet
            elseif ($_GET['action'] == "showrecovery") {
                if (isset($_POST['pass']) != "") {
                    $gulden->walletpassphrase($_POST['pass'], 5);
                    $guldenresponse = $gulden->response['error']['code'];

                    if ($guldenresponse != "-14") {
                        $returnarray = "<div class='alert alert-success'>" . $gulden->getmnemonicfromseed($gulden->getactiveseed()) . "</div>";
                    } else {
                        $returnarray = "<div class='alert alert-warning'>Wallet passphrase incorrect.</div>";
                    }

                    echo json_encode($returnarray);
                }
            } //Import a Recovery Phrase to this wallet
            elseif ($_GET['action'] == "importrecphrase") {
                if (isset($_POST['phrase']) != "") {
                    $gulden->importseed($_POST['phrase']);
                    $guldenresponse = $gulden->response;

                    if ($guldenresponse != "-14") {
                        $returnarray = "<div class='alert alert-success'>Wallet imported, starting rescan. This can take a while.</div>";
                        $gulden->rescan;
                    } else {
                        $returnarray = "<div class='alert alert-warning'>Something went wrong:<br>" . $guldenresponse . "</div>";
                    }

                    echo json_encode($returnarray);
                }
            } //Add a new account to this wallet
            elseif ($_GET['action'] == "addaccount") {
                if (isset($_POST['accountname']) != "") {
                    if (strpos($_POST['accountname'], "*") === false) {

                        //Check the passphrase and unlock the wallet for 10 seconds
                        $gulden->walletpassphrase($_POST['pass'], 10);
                        $guldenresponse = $gulden->response['error']['code'];

                        if ($guldenresponse != "-14") {
                            //Create the account
                            $createaddr = $gulden->createaccount(trim($_POST['accountname']));
                            if ($createaddr == "false") {
                                $returnarray = $gulden->response;
                            } else {
                                $returnarray = $createaddr;
                            }
                        } else {
                            //Passphrase incorrect
                            $returnarray = "-1";
                        }

                        echo json_encode($returnarray);
                    }
                }
            } //Change account name
            elseif ($_GET['action'] == "changeacc") {
                if (isset($_POST['changedacc']) != "" && isset($_POST['currentacc']) != "") {
                    if (strpos($_POST['changedacc'], "*") === false) {
                        $chaccount = $gulden->changeaccountname($_POST['currentacc'], trim($_POST['changedacc']));
                        if ($chaccount == "false") {
                            $returnarray = $gulden->response;
                        } else {
                            $returnarray = $chaccount;
                        }

                        echo json_encode($returnarray);
                    }
                }
            } //Create a transaction
            elseif ($_GET['action'] == "createtransaction") {
                if (isset($_POST['address']) != "" && isset($_POST['amount']) != "" && isset($_POST['pass']) != "" && isset($_POST['fromaccount']) != "") {
                    $sendtoaddress = trim($_POST['address']);
                    $sendtopass = trim($_POST['pass']);
                    $sendamount = trim($_POST['amount']);
                    $fromaccount = trim($_POST['fromaccount']);

                    //Validate an address before taking any action
                    $validation = $gulden->validateaddress($sendtoaddress);
                    $guldenresponse = $gulden->response;
                    $validaddress = $guldenresponse['result']['isvalid'];

                    //Check the passphrase and unlock the wallet for 10 seconds
                    $gulden->walletpassphrase($sendtopass, 10);
                    $guldenresponse = $gulden->response['error']['code'];

                    if ($validaddress == "true") {
                        if ($guldenresponse != "-14") {
                            //Create the transaction in Gulden
                            $dotransaction = $gulden->sendtoaddressfromaccount($fromaccount, $sendtoaddress,
                                $sendamount);
                            if ($gulden->response['error']['code'] == "-6") {
                                $returnarray = "-6";
                            } else {
                                $returnarray = "1";
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
        }
    }
}
session_write_close();
?>
