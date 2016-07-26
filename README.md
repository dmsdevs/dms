# Dead Man Switch

**A document release app for [ownCloud](https://owncloud.org) (minimum version 8.0 & PHP 5.4).** 

This app enables the user to specify messages and documents to be released to intended targets after a period of inactivity by the user.



## Requirements

Owncloud >= 8.0
mysql/mariadb database
working mailsetup (sending mails)
Set backgroundjobs to be triggered by cron for improved reliability



## Installation

Place this app in **owncloud/apps/**



## Warning

This app is in an early development stage, test your setup before relying on this app.

Please be aware that the date to trigger the messages is dependent on the time zone of the used server and therefore may vary a few hours from the time of the user.

Note that sending large attachments via mail is not possible. Most providers block attachments larger then 20 MB, some even smaller ones. Keep your attachments short.



### PGP

ID: 
0EF68D0A

Fingerprint:
4A35 55DD 448C 0310 8459  2504 20A5 4B65 0EF6 8D0A

