#!/bin/bash
#
# Using backup-manager to upload
#
#  bash \
#    backup.sh \
#    <namespace> \
#    <domain> \
#    <database name> \
#    <laravel database connection> \
#    <destination> \
#    <hostname> \
#    <artisan path>
#
#  -- Example
#
#  bash \
#    backup.sh \
#    parlamentojuvenil \
#    www.parlamento-juvenil.rj.gov.br \
#    kallzenter \
#    postgresql \
#    s3 \
#    corinna \
#    /var/www/kallzenter/
#
#  -- Example
#
#   bash backup.sh parlamentojuvenil www.parlamento-juvenil.rj.gov.br parlamentojuvenil-production pgsql s3 falcon /home/forge/www.parlamento-juvenil.rj.gov.br/
#   bash backup.sh pragmarx backup.pragmarx.com backup-test pgsql s3 macbookpro /Users/antoniocarlos/code/pragmarx/backup

NAMESPACE=$1

DOMAIN=$2

DATABASE=$3

CONNECTION=$4

DESTINATION=$5

HOST=$6

APP_PATH=$7

YEAR="$(date +'%Y')"

MONTH="$(date +'%m')"

NOW="$(date +'%Y-%m-%dT%H-%M-%S')"

FILE="$HOST-$DOMAIN-$DATABASE-$NOW.$CONNECTION.backup"

LOCAL_PATH="/tmp/$FILE"

REMOTEPATH="/$NAMESPACE/$DOMAIN/databases/$DATABASE/$YEAR/$MONTH/$FILE"

echo "Backuping and uploading to $REMOTEPATH..."

/usr/bin/php $APP_PATH/artisan db:backup --database=$CONNECTION --databaseName=$DATABASE --destination=$DESTINATION --destinationPath=$REMOTEPATH --compression=gzip

echo
echo "All DONE!"
echo
