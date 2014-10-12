@echo off
echo _ _ _
echo Uruchamianie sieci wirtualnego routera...
netsh wlan start hostednetwork
cls
echo # _ _
echo Wirtualny router uruchomiony.
echo Uruchamianie serwera Apache i MySQL...
C:\xampp\xampp_start.exe
cls
echo # # _
set NRPORTU=
set /P NRPORTU=Podaj numer portu: %=%
cls
echo # # #
echo Uruchamianie serwera...
cls
java -jar -Dfile.encoding=UTF8 server.jar "%NRPORTU%"