<?php
session_start();

//In case the server is very busy, lower the max execution time to 60 seconds
set_time_limit(60);

if ($_SESSION['G-DASH-loggedin'] == true) {
    include('../lib/functions/functions.php');
    include('../config/config.php');
    require_once('../lib/EasyGulden/easygulden.php');
    $gulden = new Gulden($CONFIG['rpcuser'], $CONFIG['rpcpass'], $CONFIG['rpchost'], $CONFIG['rpcport']);

    $guldenD = "GuldenD";
    $guldenCPU = GetProgCpuUsage($guldenD);
    $guldenMEM = GetProgMemUsage($guldenD);
    $returnarray = array();

    if ($guldenCPU > 0 && $guldenMEM > 0) {
        $ginfo = $gulden->getinfo();
        $gerrors = $ginfo['errors'];

        //Get information on the network regarding witnessing
        $witnessNetwork = $gulden->getwitnessinfo();

        //The total number of witness addresses
        $totalWitnesses = $witnessNetwork[0]['number_of_witnesses_total'];

        //Get all the witness accounts
        $witnessaccountsnetwork = $gulden->getwitnessinfo("tip", true);

        //Get the min-max values for weight and amount
        $minWeight = $witnessNetwork[0]['witness_statistics']['weight']['smallest'];
        $maxWeight = $witnessNetwork[0]['witness_statistics']['weight']['largest'];
        $minAmount = $witnessNetwork[0]['witness_statistics']['amount']['smallest'];
        $maxAmount = $witnessNetwork[0]['witness_statistics']['amount']['largest'];

        //Only get the witness address list	of all the witnesses in the whole network
        $witnessaddresslist = $witnessaccountsnetwork[0]['witness_address_list'];

        //Get the total amount of Gulden locked in an array and make a 3D array
        $addressLockedArray = array();
        $tempAmountTimeWeightArray = array();
        $amountTimeWeightArray = array();
        foreach ($witnessaddresslist as $networkwitnessaddresses) {
            $addressLockedArray[] = $networkwitnessaddresses['amount'];
            $tempAmountTimeWeightArray['amount'] = $networkwitnessaddresses['amount'];
            $witnesslockperiod = round(($networkwitnessaddresses['lock_period'] / 4032));
            $tempAmountTimeWeightArray['time'] = $witnesslockperiod;
            $tempAmountTimeWeightArray['weight'] = $networkwitnessaddresses['adjusted_weight'];

            $amountTimeWeightArray[] = $tempAmountTimeWeightArray;

            $tempAmountTimeWeightArray = array();
        }

        //Sort the array by number of weeks locked
        //usort($amountTimeWeightArray, mdarraysorter('time'));
        array_multisort(array_column($amountTimeWeightArray, 'amount'), SORT_ASC,
            array_column($amountTimeWeightArray, 'weight'), SORT_ASC,
            array_column($amountTimeWeightArray, 'time'), SORT_ASC,
            $amountTimeWeightArray);

        //Get the min and max values for weeks locked
        $mintime = min(array_column($amountTimeWeightArray, 'time'));
        $maxtime = max(array_column($amountTimeWeightArray, 'time'));

        //Reverse sort the array by amount locked
        rsort($addressLockedArray);

        //Return the values in an array
        $returnarray['addresslocked'] = $addressLockedArray;
        $returnarray['3d'] = $amountTimeWeightArray;
        $returnarray['3dmeta']['time']['min'] = $mintime;
        $returnarray['3dmeta']['time']['max'] = $maxtime;
        $returnarray['3dmeta']['weight']['min'] = $minWeight;
        $returnarray['3dmeta']['weight']['max'] = $maxWeight;
        $returnarray['3dmeta']['amount']['min'] = $minAmount;
        $returnarray['3dmeta']['amount']['max'] = $maxAmount;

    }

    echo json_encode($returnarray);
}
session_write_close();
?>
