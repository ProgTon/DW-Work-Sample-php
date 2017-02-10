# DANIEL WELLINGTON PHP WORK SAMPLE

This is a work sample for Daniel Wellington. The purpose of this project is to show my skills in PHP. The purpose of the project itself is for the user to be able to upload CSV-files which contains information about bank transactions. There is a function to trigger parsing of the files which will send the content to the database and will also return any errors that occurs while doing this. There is also a page where the user can see all bank transactions that has been uploaded and processed.

Online documentation: https://github.com/ProgTon/DW-Work-Sample-php

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

### Prerequisites

This web application can only run on an apache server which supports PHP and MySQL.

#### Requirements
```
* PHP (version 5.5 or higher)
* Apache Web Server (version 2.2 or higher)
* MySql (version 5.5 or higher)

```

A few examples of programs for running this application locally:

* XAMPP
* WAMPSERVER

# Installing

Following is the instructions on how to get this web application up and running

## 1. Installing a web server for local usage (I have chosen XAMPP)

Visit https://www.apachefriends.org/index.html and follow the instructions in the video.


## 2. Setup instructions

This web application folder must be in the newly created XAMPP folder.
Example of path: C:\xampp\htdocs\minphp\PROJECTFOLDER
The folder 'minphp' can be named to whatever.
The folder 'PROJECTFOLDER' is the name of the project root folder.


## 3. Setup instructions for the folders which will contain the CSV-files

Open up config/folderconfig.php in ur texteditor.
There are 3 variables that can be configured. UnprocessedFolder, ProcessFolder and HistoryFolder.

## 4. Setup instructions for the database

Start by opening up phpMyAdmin by clicking "Admin" in the MySQL-row in XAMPP.
This should open up your standard browser with the phpMyAdmin-page.
Next up, create a database. When a database has been created, you should see your new database with its name in the left sidebar. The collation to use is UTF-8 (utf8_general_ci).
Enter your new database by clicking it. Then click 'import' in the topmenu and choose the sql-file named 'database' in the setup directory of the project.
Open up config/dbconfig.php in a texteditor and enter the exact values for each of the 4 variables.

## 5. Tests to see that everything is running properly

Visit the URL for the index.php-file in this project.
Find the first part the URL by clicking 'Admin' in the Apache-row in XAMPP.
This should open up your standard browser with the dashboard-page.
Now remove /dashboard/ from the URL and enter the path to this project.
Example: localhost/dashboard/ will become localhost/minphp/index.php.

If all goes well you should be taken to the frontpage with the heading 'Bank Transactions'.


# File-test.
These tests are to see that the application is accepting the expected files.

## File-structure
The CSV-files must be structured as such:

| ID  | Date      | Description         | Amount    |
| --- | --------- | ------------------- | --------- |
| 1   | 17/02/28  | DANIEL WELLINGTON   | -1395     |
| 2   | 17-12-31  | GOOGLE              | -3000     |
| 3   | 17/12/31  | APPLE               | -7800     |


However, there are proper error-messages when the app is used the wrong way.
Examples for wrong ways are CSV-files structured the wrong way.


## 1. Upload a file

The project-folder comes with a testfile in the 'testfiles'-folder.
After uploading a file, you will be redirected to the 'Uploading files'-page. The file uploaded should have the status 'success'.
If uploaded files have the status 'Fail', the reason for failure will be under the column 'Messages'.
When a file is successfully uploaded, the file is saved in the directory 'csv/unprocessed/'.
This also means that the file has been saved in the database table 'file' with its
filename, total number of rows in the file and creation date (timestamp from when the file was uploaded).

## 2. Manage uploaded files

### 2a. Process file

From the homepage, click 'Manage uploaded files'.
There are 3 tabs on this page. Unprocessed, History and Under process.
Under the 'Unprocessed'-tab, you will find the testfile you just uploaded. You now have 2 options. Process or Delete.
* Processing the file will send its content to the database.
* Deleting a file will delete all data connected to it in the database aswell as the file itself from the directory.

When the processing of the file begins, the current timestamp is saved in a variable as 'StartDate'.
During the process, the file will be moved from the 'unprocessed'-folder to the 'processing'-folder.
A variable will be saved with the total number of errors.
After the process has finished, the current timestamp is saved in a variable as 'EndDate' and the filerow in the database table 'file' is updated with its new values: RowsWithErors, StartDate and EndDate.

Any errors that occurred during the process will be displayed when the file has been fully processed.
When the chosen files have been processed, you will enter a state where you can't switch tabs before reloading/refreshing the page.
You will only be able to view the eventual errors during this state. There will be a refresh button displayed on the page.

### 2b. View processed file

Click the 'History'-tab to view the processed files. Here you can see various information of each file processed.


## 3. View transactions

From the homepage, click 'View transactions'.
On this page you can see all the transactions from every file you have uploaded.


## Built With

* HTML - Client-side programming-language
* CSS - Client-side styling programming-language
* JavaScript - Client-side script programming-language
* jQuery - Client-side JavaScript Library
* PHP - Server-side programming-language
* SQL - Server-side database programming-language (MySQL)

## Authors

* ** Anton Karlsson ** - (https://se.linkedin.com/in/anton-karlsson-244251132)
