#!/bin/bash
set -e

if [ $# -eq 0 ]
  then
    echo "The format is create-api.sh API"
    exit
fi

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"
if [ -f $SCRIPT_DIR/.env ]
then
  export $(cat $SCRIPT_DIR/.env | sed 's/#.*//g' | xargs)
fi

API_NAME=$1

API_TARGET="$APIS_PATH"$API_NAME

mkdir -p $API_TARGET
cp -R "$SCRIPT_DIR"/api-template/* $API_TARGET
sed -i "s/#api-name#/$API_NAME/g" "$API_TARGET"/api.yaml

echo "api created: "$API_TARGET