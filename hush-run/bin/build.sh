#!/bin/sh

BaseDir=$(pwd)
FileDir="$BaseDir/../"

mkdir -p "$FileDir/sys"
mkdir -p "$FileDir/sys/php/session"

mkdir -p "$FileDir/cdn/hush-app"
mkdir -p "$FileDir/dat/hush-app"
mkdir -p "$FileDir/run"
mkdir -p "$FileDir/run/hush-app/tpl/default"
mkdir -p "$FileDir/run/hush-app/log/default"
mkdir -p "$FileDir/run/hush-app/cache/default"
