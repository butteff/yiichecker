# Yiichecker
console yii2 command, which helps to check url (or file of urls) for Yii framework fingerprints (does a website use yii or not)

how to use:
1. Upload YiicheckController file to your commands folder (for Basic Yii template) or to console/controllers (for Advanced Yii template)
2. Go to your root folder of Yii framework
3. Execute the **command to check a single url**:
```
    ./yii yiicheck/url www.yiiframework.com
```
or Execute the **command to check file of urls**:
```
./yii yiicheck/file /home/websites_to_check_file.txt(file with websites urls, one per line, [required]) /home/detected_yii.txt(output file [optional]) true( show output to screen or not [optional, default true])
```
