@echo off

set SysPath=%~pd0\..\sys
set CdnPath=%~pd0\..\cdn
set DatPath=%~pd0\..\dat
set RunPath=%~pd0\..\run
set AppPath=%~pd0\..\run\hush-app

echo building sys path...
md %SysPath%
md %SysPath%\php\session

echo building cdn path...
md %CdnPath%\hush-app

echo building dat path...
md %DatPath%\hush-app

echo building run path...
md %RunPath%
md %AppPath%
md %AppPath%\tpl\default
md %AppPath%\log\default
md %AppPath%\cache\default

pause