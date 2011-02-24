@echo off

REM -------------------------------------------------
REM Please enter the absolute path to your java binary
REM -------------------------------------------------
SET JAVA_BIN=C:\Program Files (x86)\Java\jre6\bin\java.exe

REM -------------------------------------------------
REM Please enter the absolute path to your selenium server .jar file
REM -------------------------------------------------
SET SELENIUM=C:\Program Files (x86)\selenium\selenium-server-1.0.3\selenium-server.jar

call "%JAVA_BIN%" -jar "%SELENIUM%" -interactive