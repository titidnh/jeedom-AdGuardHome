PROGRESS_FILE=/tmp/dependancy_AdGuardHome_in_progress
if [ ! -z $1 ]; then
	PROGRESS_FILE=$1
fi
touch ${PROGRESS_FILE}
echo 0 > ${PROGRESS_FILE}
echo "********************************************************"
echo "*             Installation des dépendances             *"
echo "********************************************************"
apt-get update
echo 30 > ${PROGRESS_FILE}
apt-get install python3 python3-pip
echo 50 > ${PROGRESS_FILE}
pip3 install --upgrade pip
pip3 install -U adguardhome
echo 100 > ${PROGRESS_FILE}
rm ${PROGRESS_FILE}
echo "********************************************************"
echo "*             Installation terminée                    *"
echo "********************************************************"