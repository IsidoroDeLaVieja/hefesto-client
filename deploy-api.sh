#!/bin/bash
set -e

if [ $# -eq 0 ]
  then
    echo "The format is deploy-api.sh ENV API"
    exit
fi

if [ $# -eq 1 ]
  then
    echo "The format is deploy-api.sh ENV API"
    exit
fi

SCRIPT_DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" &> /dev/null && pwd )"

if [ -f $SCRIPT_DIR/.env ]
then
  export $(cat $SCRIPT_DIR/.env | sed 's/#.*//g' | xargs)
fi

DEPLOY_ENDPOINT=DEPLOY_ENDPOINT_${1}
DEPLOY_ENDPOINT=${!DEPLOY_ENDPOINT}
API_KEY=API_KEY_$1
API_KEY=${!API_KEY}
PUBLIC_HOST=PUBLIC_HOST_$1
PUBLIC_HOST=${!PUBLIC_HOST}

API_NAME=$2
BUILD=$SCRIPT_DIR/build

if [ -d "$BUILD" ]; then rm -Rf $BUILD; fi
mkdir $BUILD

cd $APIS_PATH
cp -R $API_NAME/Directives $BUILD
cp -R $API_NAME/Maps $BUILD
cp -R $API_NAME/Assets $BUILD
cp $API_NAME/api.yaml $BUILD
cp $SCRIPT_DIR/Directives/*.php $BUILD/Directives

cd $BUILD
tar -czvf $API_NAME.tar.gz * > /dev/null
curl --location --request POST $DEPLOY_ENDPOINT --header 'public-host-key: '$API_KEY --header 'public-host: '$PUBLIC_HOST  --form 'file=@"'$BUILD/$API_NAME'.tar.gz"'
rm $API_NAME.tar.gz

echo ''
