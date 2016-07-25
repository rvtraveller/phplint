#!/bin/bash

# Gets the directory of this script file, also resolving symlinks:
# (reference: Dave Dopson, http://stackoverflow.com/questions/59895/can-a-bash-script-tell-what-directory-its-stored-in )
SOURCE="${BASH_SOURCE[0]}"
while [ -h "$SOURCE" ]; do # resolve $SOURCE until the file is no longer a symlink
	DIR="$( cd -P "$( dirname "$SOURCE" )" && pwd )"
	SOURCE="$(readlink "$SOURCE")"
	[[ $SOURCE != /* ]] && SOURCE="$DIR/$SOURCE" # if $SOURCE was a relative symlink, we need to resolve it relative to the path where the symlink file was located
done
__DIR__="$( cd -P "$( dirname "$SOURCE" )" && pwd )"

/opt/php/bin/php "-c$__DIR__/stdlib" "$@"
