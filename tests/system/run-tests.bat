@echo off

REM -------------------------------------------------
REM Please enter the absolute path to your php binary
REM -------------------------------------------------
SET PHPBIN=C:\Program Files (x86)\php-5.3.2-nts\php.exe

REM -------------------------------------------------
REM Please enter the absolute path to your PHPUnit location
REM -------------------------------------------------
SET PHPUNIT=C:\htdocs\phpunit\PHPUnit-3.4.14

"%PHPBIN%" "%PHPUNIT%\phpunit.php" -d include_path="./;%PHPUNIT%" "%CD%\suite\TestSuite.php" > virgin-run.txt

pause