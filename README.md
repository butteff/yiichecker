# Yiichecker
Console Yii2 command, which helps to check any url for Yii framework fingerprints (does a website use yii or not). It also can check a list of urls (from the file). It can output results to a file (if you need) and work in a silent mode (optional parameter too).

## How to use:
1. Upload YiicheckController file to your commands folder (for Basic Yii template) or to console/controllers (for Advanced Yii template)
2. Go to your root folder of Yii framework
3. Execute the **command to check a single url**:
```
    ./yii yiicheck/url www.yiiframework.com
```
4. you can execute the **command to check file of urls** (one url per line):
```
./yii yiicheck/file input_file_path output_file_path[optional, you can skip it] show_output[optional (default true)]
```
for example:
```
./yii yiicheck/file /home/websites_to_check_file.txt /home/detected_yii.txt true
```
![terminal usage example](https://user-images.githubusercontent.com/1950858/60846193-59587f00-a1e7-11e9-842a-a6fd341a7d6a.png)
