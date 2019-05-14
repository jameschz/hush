@echo off

set SysPath=%~pd0\..\sys
set CdnPath=%~pd0\..\cdn
set DatPath=%~pd0\..\dat
set RunPath=%~pd0\..\run

echo building sys path...
md %SysPath%
md %SysPath%\php\session

echo building cdn path...
md %CdnPath%\hush-app

echo building dat path...
md %DatPath%\hush-app

echo building run path...
md %RunPath%
md %RunPath%\hush-app\tpl\default
md %RunPath%\hush-app\log\default
md %RunPath%\hush-app\cache\default

pause