#! /bin/bash 
crontab -l > cronbackup ; 
echo "docker exec -it navbellapi_api_1 bash /var/backups/backup.sh"  >> cronbackup ; 
crontab cronbackup ;
rm cronbackup 