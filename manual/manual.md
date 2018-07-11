# G-DASH USER MANUAL

Version 1

July 11 2018



[Download this manual as a PDF document](https://g-dash.nl/manual/G-DASH_manual.pdf)





&copy; Bastijn Koopmans - [G-DASH.nl](https://g-dash.nl)







# Table of Contents

[TOC]



# Introduction

## Summary

G-DASH is a lightweight, responsive, web-based user interface for Gulden users who run a wallet, node, witnessing account, or all of the above on a Linux server (i.e. an Ubuntu VPS or a Raspberry Pi). With this dashboard, users can keep an eye on their Gulden server and control their node and witnessing account without the need of a terminal. It also includes the option to control a Gulden wallet, and the software has an auto-update functionality to make sure you always run the latest version of G-DASH.  



## About G-DASH

When the PoW^2^ whitepaper was released and the witness functionality was described, I started thinking about a way to have a wallet running 24 hours a day, 7 days a week and combining it with the option of running a full node to strengthen the network. Leaving a computer running with the wallet software day and night is a waste of energy (and cost), and renting a server for this purpose would be a bit overkill as well. Hence I started experimenting on a headless Raspberry Pi (a mini-computer without a screen/mouse/keyboard). The board itself costs about 30 Euro, and with a case, SD card, etc it is still a cheap computer fully capable of running a Gulden node.

So, I got everything working on the Pi. My Gulden node had incoming connections. Cool! But every time I wanted to check the node, I had to log in to my Pi and type a few commands to eventually see a load of text and numbers on my screen. “I should make a web interface for this, so I don’t have to log in every time” was my thought. That said, I started a small project that gave me some simple output. However, this was still the same load of text and numbers.

I like dashboards and use it every day (like Google Analytics, the status of my servers at work, etc), so I started with a simple dashboard for Gulden on Linux. While creating the dashboard, I found more people from the Gulden community were interested in this project and could benefit from this. The small project became a real project. I spoke with a few people who had a Pi collecting dust or some space left on their VPS/server and were happy to help testing the software and give their opinion to improve the software. So this project moved from a “5-minute quick and dirty” project, to an “actual project” to a “wow, 10 people are testing the software” project.

In the first year, many beta versions were pushed and everyone could download the software and test it out before a full release (version 1) was ready.



# Installation

## System requirements

A Linux server running the following packages:

- curl 
- apache2 
- php (> 5.3)
- libapache2-mod-php 
- php-curl 
- php-json 
- php-cli 



## Installation from archive

Download the latest version from [G-DASH.nl](https://g-dash.nl) and upload this to your server. Make sure you set the permissions of G-DASH to your webserver (for example 'www-data'). 



## Step-by-step installation instructions (Raspberry Pi 3B)

### Introduction

 These instructions are broken down in different parts and are based on an installation on a Raspberry Pi 3B. If you have never done anything with Linux, start at the beginning. If you are a more experienced user, check each step carefully and skip the steps you don't need (or already have done). Note that all the directories mentioned below can be changed to other directories. For the guide below I used the following folders:

- Gulden base folder: `/opt/gulden/`
- Gulden binaries: `/opt/gulden/gulden/`
- Gulden data directory: `/opt/gulden/datadir/`
- Home directory: `/home/pi/`
- G-DASH directory: `/var/www/g-dash/`



The versions used for this guide are currently:

- G-DASH: `0.995`
- Gulden: `2.0.0.2`

 

### Installing your Raspberry Pi

- Install Raspbian on the Raspberry Pi. Follow the steps on <https://www.raspberrypi.org/downloads/raspbian/>
- Create a file on the SD cards' `/boot` partition called `ssh`. For details see <https://www.raspberrypi.org/blog/a-security-update-for-raspbian-pixel/>
- When finished, insert the SD card in the raspberry pi and connect using any SSH client (i.e. for Windows you can use [Putty](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html)).
- Run `sudo raspi-config` and change the user password, enable SSH server and change the localisation options
- Make sure you update Raspbian once in a while with `sudo apt-get update && sudo apt-get upgrade`

  

### Configure the Pi

- Install Curl, Apache and PHP:
  `sudo apt-get -y install curl apache2 php libapache2-mod-php php-curl php-json php-cli`
- After installing these programs, you might see this message: "Warning: Unit file of apache2.service changed on disk, 'systemctl daemon-reload' recommended." If so, run the following command:
  `sudo systemctl daemon-reload`
- Edit the settings of the apache installation (i.e. the root folder of the webserver)
- The root folder of your webserver can be edited by "root" in `/etc/apache2/sites-enabled/000-default.conf`. Replace the root folder (default is /var/www/html) by the folder where you want to install G-DASH (in this example /var/www/g-dash).

 

### Install Gulden on your Pi

- Add the Gulden for Raspbian repository to the APT sources:
  *For Raspbian Stretch (released September 2017):*
  `sudo sh -c 'echo "deb http://raspbian.gulden.com/repo/ stretch main" > /etc/apt/sources.list.d/gulden.list'`

- Update APT and install Gulden:
  `sudo apt-get update && sudo apt-get -y --allow-unauthenticated install gulden`

- Make the Gulden files executable:
  `sudo chmod -R a+rwx /opt/gulden/gulden`

- Make pi the owner of the gulden folder:
  `sudo chown -R pi:pi /opt/gulden/`

- Create a Gulden.conf file in the datadir using the text editor of your choice (I allways go with "joe" - sudo apt-get -y install joe): `joe /opt/gulden/datadir/Gulden.conf`

- Add the required configuration commands:
  `maxconnections=60`

  `rpcuser=xxx`

  `rpcpassword=yyy`

  (xxx and yyy are securely generated settings of your own that you have chosen.)

- 20 is the recommended setting for low end machines, for higher system specifications set this to a higher number. The Raspberry Pi can handle at least 60 without problems.

- Save the file (with joe press `CTRL + K` and then `X`)



### Install G-DASH

- Go to your /home/pi folder:
  `cd /home/pi`
- Download the latest version of G-DASH using "wget":
  `wget https://g-dash.nl/download/G-DASH-0.995.tar.gz`
- If not done already. Create the folder where you want to install G-DASH:
  `sudo mkdir /var/www/g-dash`
- Extract the file in the web folder of your Pi (note: typically /var/www/ but you can change this to whichever directory inside the "www" folder):
  `sudo tar -xvf G-DASH-0.995.tar.gz --directory /var/www/g-dash`
- Copy the sample config to create an actual config file:
  `cp /var/www/g-dash/config/config_sample.php /var/www/g-dash/config/config.php`
- Make www-data the owner of the web folder:
  `sudo chown -R www-data:www-data /var/www/g-dash/`
- Restart apache to apply any changes:
  `sudo service apache2 restart`
- Go to the webaddress of your Pi and setup G-DASH (follow the instructions)
- Note that the website will not work fully until Gulden has fully synced the first time (this can take an hour or so). You can see the progress of the sync in the dashboard main screen.



### Start GuldenD and optionally create a startup script for GuldenD

- Create a new bash file in /opt/gulden/ (again, my favorite text editor is joe):
  `joe /opt/gulden/guldenstart.sh`

- Copy/paste the following lines (don't forget to edit the paths if applicable):

  ```bash
  echo "Stopping GuldenD service"
  /opt/gulden/gulden/Gulden-cli -datadir=/opt/gulden/datadir stop
  sleep 5
  echo "Killing GuldenD"
  killall -9 GuldenD
  sleep 5
  echo "Checking for Gulden update"
  sudo apt-get update
  sudo apt-get -y --allow-unauthenticated install gulden
  sleep 5
  echo "Removing peers.dat"
  rm /opt/gulden/datadir/peers.dat
  sleep 5
  echo "Starting GuldenD"
  /opt/gulden/gulden/GuldenD -datadir=/opt/gulden/datadir &
  ```

- Save the file (with joe press `CTRL + K` and then `X`)

- Give this script execution rights:
  `sudo chmod a+rwx /opt/gulden/guldenstart.sh`

- Add this script to the crontab so it will start the GuldenD on boot:
  `crontab -l | { cat; echo "@reboot sleep 30 ; /opt/gulden/guldenstart.sh 2>&1"; } | crontab -`

- Now you can reboot the pi and the GuldenD will start automatically, or you can run guldenstart.sh:
  `/opt/gulden/guldenstart.sh`

- If you want to run a script that checks if GuldenD is running (every 5 minutes), and automatically restart GuldenD if it has somehow stopped or crashed, you can use the following script and cronjob.

- Create a new bash file in /opt/gulden/:
  `joe /opt/gulden/guldendchecker.sh`

- Copy/paste the following lines (don't forget to edit the paths if applicable):

  ```bash
  \#!/bin/sh
  \# set -x
  \# Shell script to monitor GuldenD running on the G-DASH node
  \# If the number of GuldenD processes is <= 0
  \# it will start GuldenD.
  \# -------------------------------------------------------------------------
  \# set alert level 0 is default
  ALERT=0
  \#
  \#::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
  \#
  usep=$(ps aux | grep GuldenD | wc -l | awk '{print $1-1}')
  echo $usep
  if [ $usep -le $ALERT ] ; then
  /opt/gulden/guldenstart.sh
  fi
  ```

- Save the file (with joe press `CTRL + K` and then `X`)

- Give this script execution rights:
  `sudo chmod a+rwx /opt/gulden/guldendchecker.sh`

- Add this script to the crontab:
  `crontab -l | { cat; echo "*/5 * * * * sleep 120 ; /opt/gulden/guldendchecker.sh >/dev/null 2>&1"; } | crontab -`



## Auto installation script (Raspberry Pi 3B)

### Introduction

This is a script that will automatically do all the steps described in the detailed instructions above:

- Install apache, PHP, Curl and their prerequisites.
- Download and install Gulden.
- Download and install G-DASH.
- Create an auto-start script for Gulden and add this to the crontab to start at boot



<u>WARNING!</u>

This script has only been tested on a freshly installed Raspberry Pi 3B with Raspbian Stretch. Do not use this script if you are already using the Pi for other purposes as it may delete folders and reconfigure software. Disclaimer: Be very careful when using this method! Using this method is at your own risk, I am not responsible for broken down systems or any other problems when using this script. Read through the bash script first if you have any doubts or concerns!

 

### Installing your Raspberry Pi

- Install Raspbian on the Raspberry Pi. Follow the steps on <https://www.raspberrypi.org/downloads/raspbian/>
- Create a file on the SD cards' `/boot` partition called `ssh`. For details see <https://www.raspberrypi.org/blog/a-security-update-for-raspbian-pixel/>
- When finished, insert the SD card in the raspberry pi and connect using any SSH client (i.e. for Windows you can use [Putty](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html)).
- Run `sudo raspi-config` and change the user password, enable SSH server and change the localisation options
- Make sure you update Raspbian once in a while with `sudo apt-get update && sudo apt-get upgrade`



### Installation

- Login to your Pi using SSH (for example on Windows use [Putty](https://www.chiark.greenend.org.uk/~sgtatham/putty/latest.html))
- Run this command to download, extract and start the auto installer:
  `wget https://g-dash.nl/download/autoinstall-G2.0.0.2-D0.995.tar.gz && tar -xvf autoinstall-G2.0.0.2-D0.995.tar.gz && chmod -R a+rwx autoinstall.sh && source autoinstall.sh`
- Login to G-DASH (as displayed on the screen after installation).



# Configuration

## Opening G-DASH for the first time

The first time you open G-DASH you will automatically be redirected to the settings page. Please go through these settings and read through all the options carefully. All settings are divided in tabs, and most settings are self explanatory or have an explanation below that specific setting.



## G-DASH

In the G-DASH settings tab you can set up the general settings for the dashboard.

**G-DASH username:** The username you want to use to login to the dashboard.

**G-DASH password:** The password you want to use to login to the dashboard.

**Dashboard web address: **The address that you see in the address bar of your browser (i.e. http://192.168.1.10).

**Disable login screen: **If this checkbox is enabled, the login screen is disabled. Note that everyone in your network can access the dashboard without the need to login. If you forwarded your G-DASH instance in your router to make it accessible from outside your network, and you have disabled the login screen, people who know or guess your IP address can enter G-DASH. Please make sure you know what you are doing if you enable this checkbox.

**Use the beta update channel: **By enabling this checkbox you will receive Beta updates of G-DASH. These updates might be unstable and contain bugs, so it's generally not advised unless you would like to help testing new releases. This setting should not be enabled unless you have some knowledge of the changes made in the scripts.

**Use 2-factor authentication: **With this setting enabled you need to scan the QR code shown below this checkbox using a 2FA app on your phone (i.e. Authy or Google Authenticator). If this setting is enabled, you will need to enter a 6 digit code, which changes every 30 seconds, next to your username and password when you log in to G-DASH. Make sure to save this QR code in a safe place. If you lose access to your phone or somehow lose the app, you will not be able to login the usual way. If this is the case, please refer to the "Troubleshooting" section in this document on how to solve this.



## Notifications

G-DASH allows you to send notifications to your phone or browser using [PushBullet](https://pushbullet.com). A script in the background will check every 2 minutes for actions related to you wallet, witness accounts and the Gulden server, and then sends a message using the PushBullet service. The last message that was sent is stored in G-DASH and is visible underneath the help text.

**PushBullet access token:** An access token is needed for the service to know where to send the messages to. You can create an account on their website and on the account settings page you can find your access token.

**Send a notification if the Gulden server is down: ** Check this box if you want to receive a message when your GuldenD server is down. If you installed Gulden and G-DASH using the above mentioned scripts, G-DASH will try to restart Gulden automatically. You will also receive a message when Gulden is up again.

**Send a notification if an update of G-DASH is available:** Receive a push message if there is a new version available for G-DASH.

**Send a notification when there is an update for Gulden:** Receive a push message if there is a new version available for Gulden.

**Send a notification when Guldens are received:** Receive a push message when you have received Gulden on your wallet.



## Node

Your Gulden application automatically runs as a node, but it can also become a full node. The difference is that you only connect to other people to receive the blocks (outbound) when you are a normal node, opposed to also receiving connections from other nodes (inbound) that receive block information from your device. Full nodes are needed to secure the network and are also needed to supply block information to others (like your Gulden desktop wallet or phone wallet, it's always searching for 8 nodes).

The options listed below gives everyone an idea of how many people are using Gulden applications, from where they are used and which versions are used. With the help of [GuldenNodes.com](https://guldennodes.com) these instances are also nicely mapped.

**Upload node statistics:** If you enable this feature, the Gulden instances you connect to, and the instances that connect to you, are visible on the GuldenNodes map. Don't worry about the safety, as it's not possible to find the exact address, but only the city where this Gulden app is currently.

**Allow Node Requests:** This feature helps people who have trouble being found as a full node. Users can request to be added by others, and if this is enabled on your node, you will help this person by adding him to your outbound connections for a maximum of 24 hours. This gives the network enough time to pick this person up, so the device can be found by others. This in turn again strengthens the network.



## Wallet

The wallet is where you can store your Gulden. There is currently one option in the wallet.

**Rate provider: ** The exchange where the current Gulden rate is fetched from. This is used to calculate what the value of Gulden is in the selected currency. The current choices are Euro, US Dollar and Bitcoin.



## Gulden

The settings in the Gulden tab are used for connecting to the Gulden application. This connection is needed for G-DASH to function, as it's getting the information directly from the Gulden application. 

**GuldenD location:** The location of the Gulden binaries on the server. This can also be a remote connection, although this is not recommended.

**Data location:** The location of the data directory containing the blockchain, wallet.dat and the Gulden.conf.

**RPC username:** The RPC (Remote Procedure Call) username as shown in the Gulden.conf. This is needed to talk to Gulden.

**RPC Password: ** The RPC password as shown in the Gulden.conf. This is needed to talk to Gulden.

**Host address:** The address of the Gulden application where G-DASH has to connect to. Usually this is the same server, so the default is "localhost" or "127.0.0.1".

**Host port:** The port the RPC server is listening on. The default is 9232, and unless changed manually in the Gulden.conf file or if running on testnet (9924), this shouldn't be changed.



# Using G-DASH

## Overview (main dashboard screen)

The overview page shows the basic information about the Gulden application running. Here the version of Gulden and sync status can be found, as well as information on the last 10 blocks and current status of inbound/outbound connections and server load.



## GuldenD

The information on this page shows the server health (CPU and memory usage, and current temperature of the CPU). The GuldenD page also shows more detailed information about the current sync status, time online and number of blocks on the network as well as the number of synced blocks.



## Node

If this instance of Gulden is set up to be a full node, this page shows information about the number of inbound connections to the Gulden Daemon. It also gives a list of the locations and version numbers that are connected to the server.



## Wallet

### Accounts

The wallet works the same as the desktop wallet. It shows a list of the accounts available in the wallet and new accounts can be created using the "( Add account )" link at the bottom of the account list. The amount of Gulden listed on the top of the account list is the total amount available in your wallet, including the Gulden locked in a witness account. When multiple accounts are created, the details of each account are shown once they are clicked on. It's also possible to rename the account by clicking on "( Rename account )" in the header of the second overview. The lock in the top left corner shows if your wallet is locked (green) or unlocked (red). When a transaction has been made to another wallet, it's possible the lock turns red, as it needs to unlock in order to send Gulden. It will automatically lock again after that and the lock should turn green.



### Account details

When an account is selected, the details show up in the second overview. If no account is selected, it automatically gets the information from the first account. This overview shows the balance of the selected account, and the receiving address together with a QR code that can be scanned by a mobile wallet.



### Account actions

When using the wallet page for the first time after installation, G-DASH will ask you to either encrypt your wallet or to recover a wallet using the recovery phrase.

If the wallet is completely set up and encrypted, other actions become available.

**Change wallet password:** Change the encryption password of your wallet. This password is needed to send funds or to lock Guldens in a witness account.

**Show the recovery phrase:** When this option is selected, it will ask for the encryption password as mentioned above. If the correct password is supplied, G-DASH shows the 12 words with which the wallet can be recovered. <u>It is very important you write these 12 words down if you have funds in your account!</u>

**Create a transaction:** This option allows you to send Gulden to another Gulden address.



### Transaction history

The transaction history shows the 30 most recent transactions made with this account. This number is limited (as opposed to the desktop or mobile app) as all data is fetched directly from the Gulden application and is not stored anywhere on your device.



## Witness

The witness screen holds all the information about the general witness network activity, as well as the witness accounts on this wallet. More information about the witness screen will be added to this manual later on.



# Advanced settings pages

## Upgrade

When an update for G-DASH is available, it can be upgraded from this page. When a message is shown at the top of the screen, it contains a link that leads to this page. If there is no update available, it will only show that the latest version is already installed. When a new version is available, it shows the changelog and the option to update automatically.



## Config Check

If there are problems with Gulden or with G-DASH, this page can be used as a reference for where the problem may come from. It checks if you have all the required packages installed on the device, and if required files are available and accessible. At the bottom it checks if the details that were entered in the Gulden settings page (RPC username and password) match with the Gulden.conf file. If this is not the case, G-DASH can't connect to Gulden.

**Prerequisites:** This part shows a list of all the packages needed by G-DASH and Gulden to function on the server. If one of the packages is red, it needs to be installed.

**Gulden:** The Gulden checks contain a list of files and their file permissions. If the file permissions of the files are not set correctly, and there are problems with the Gulden installation, this could be the cause. The most important permissions are: 

​	<u>Gulden.conf</u> is readable by G-DASH.

​	<u>GuldenD</u> is executable.

​	<u>Gulden-cli</u> is executable.

The "least important" one is the <u>debug.log</u> file. As G-DASH contains a function to read the log file from the Debug Console, it could happen an error is thrown saying the file is not readable. This is nothing to worry about, it only means the file can not be accessed from within G-DASH and should be accessed directly on the server. To change the permissions of the debug.log to being readable by G-DASH, the following command can be executed on the server (assuming a basic installation as mentioned in the installation guide; file paths may differ from this example):  `chmod 0644 /opt/gulden/datadir/debug.log`

**Listening services:** A list of services running on the server that are able to receive commands. Here it is possible to check if the RPC server (port 9232) is listening and if the full node port is listening (port 9231).

**Full node port forward:** A check if a remote service is able to connect to port 9231.

**G-DASH:** This part shows if the username and password entered in the "Gulden settings" tab are the same as found in the Gulden.conf file. If this is not the case, the RPC server will not accept requests.



## Debug Console

The debug console can be used to supply commands directly to Gulden or to fetch information which may be useful for debugging. When the command `help` is entered, it returns a list with the current commands available.

**help** - Show available commands

**getinfo** - Get GuldenD info

**showlog** - Show the last 50 lines of the Gulden debug log

**addnode** - Add a node by IP address (usage: addnode IP)

**noderequest** - Add a request to be added by other nodes

**walletunlock** - Temporary unlock the wallet for Gulden services

**rescan** - Rescan the blockchain for transactions

**getrescanprogress** - Rescan progess in percentage

**guldenstop** - Stop GuldenD graciously (i.e. before a reboot)



## Changelog

The changelog page shows all the changelogs of the past updates. A lot of information can be found on what is available, and what is being worked on.



# Command line options

G-DASH contains a small command line interface (CLI), which can be used if access to G-DASH is lost. The CLI can be reached by calling: `php /var/www/html/gdcli` 

The CLI listens to several commands. When the command `php /var/www/html/gdcli help` is called, it will return the options available in the CLI.

**help** - Shows this list of commands
**reset_2fa** - Disable the Two Factor Authentication
**reset_login** - Disable 2FA and login screen

If the command is executed, but an error is returned, it is possible there are issues with the rights of the files it tries to call on or write to. In that case the command should be called using  `sudo`, for example:  `sudo php /var/www/html/gdcli help`



# Upgrading G-DASH

G-DASH contains an auto-updater which checks if a new version is available for your installation when you log in, and if installed, via a push message. To upgrade your G-DASH installation, simply press the update button shown in the message when you log in.



# Upgrading Gulden

If you used the Raspbian repository, simply run `sudo apt-get update && sudo apt-get upgrade` to install the latest version. This update process can also be triggered by running `/opt/gulden/guldenstart.sh` if installed.

On other systems, or if the Raspbian repository is not used, you can download the Gulden binaries from [Gulden.com](https://Gulden.com), stop the Gulden server, and replace the binaries.



# Troubleshooting

## Unable to login to G-DASH

If the password is lost, this can be reset by the G-DASH Command Line Interface. For details, see the "Command line options" chapter in this manual.



## Lost 2FA codes

In case you lost your 2FA codes, or access to the device containing the 2FA codes is lost, the 2FA settings can be disabled by  the G-DASH Command Line Interface. For details, see the "Command line options" chapter in this manual.



# FAQ

The Frequently Asked Questions (FAQ) part of this manual contains questions and answers to the (surprisingly) frequently asked questions.

## I want to change my RPC password. How do I do this?

First, edit the "Gulden.conf" file in the datadir of your Gulden installation. Here you can change the password. Then go to G-DASH and change the RPC password there as well (in the section "Gulden settings"). When the passwords on both places are changed, restart Gulden by either restarting your Pi, or by running "autostart.sh".



## I opened TCP port 9231 as requested, but I still get the message "No inbound connections. Did you open/forwarded port 9231?".

It can take a while before the first clients connect to your node. Please check again in 30 minutes / 1 hour. If there are still no inbound connections, check if you forwarded the port correctly. If you go to the "Config check" page in the Settings menu, you can check if your port is forwarded correctly. If you don't know how to forward the port on your router, check if your router is listed on this website: <https://portforward.com/>



## I want to submit my node statistics, but I get the message "You have no incoming connections. You have to configure your node to enable this option".

If you have opened your port on your router, please wait for about 30 minutes / 1 hour before the first connections will be made to your node. If there are still no inbound connections, check if you forwarded the port correctly. If you go to the "Config check" page in the Settings menu, you can check if your port is forwarded correctly. If you don't know how to forward the port on your router, check if your router is listed on this website: <https://portforward.com/>



## I want to change the maximum number of connections, how can I do this?

You can change this number in the "Gulden.conf" file in the datadir of you Gulden installation.



## Why can't I change the Gulden.conf file from the dashboard?

For security reasons, G-DASH will only read from the Gulden.conf file. If your credentials would be compromised and others have access to your G-DASH, they will be able to change your Gulden.conf file which is a risk for yourself.



## When I open my wallet in G-DASH for the first time, it is empty!

The wallet in G-DASH is a new wallet. There are no funds there yet. You can transfer some Gulden to your wallet in G-DASH using the address shown or by scanning the QR code.



## I want to see my Recovery Phrase, but I get the error the password is wrong, but I did not set a password.

If you used the auto installer, the default password for the wallet is "changeme" (without the quotes).



## I want to get a notification when my computer or internet connection is down, but how can I do that?

If you want to be notified when your computer running Gulden is offline, you can use the monitoring service from [uptimerobot.com](https://uptimerobot.com/). This service checks your connection every 5 minutes. Just create an account, add a new monitor (type 'Port'), enter your IP address and port (custom port) '9231'. They also have a mobile app so you can receive push messages.



## I'm 100 percent sure I set up my full node correctly, but I still don't get any incoming connections. What's wrong?

First of all. Your system is not broken or anything if you have no incoming connections. But to make you a bit more discoverable, you can now use the `noderequest` function in the debug console (within G-DASH --> settings) that adds your node to a database. This database is then checked automatically by other G-DASH users and these instances (max 10) then make a connection with you for 24 hours, which makes you more discoverable to other nodes and seeds. When 10 nodes have connected to your node, you are removed from the database.



## G-DASH can't connect to Gulden, but the config check says username and password match.

The RPC server can't handle passwords that contain some special characters, such as "#" and "$". If you have entered a password in Gulden.conf and the "Gulden" settings tab in G-DASH, create a new password without these symbols.

