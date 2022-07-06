# Ultimate Image Uploader - UIU

### @author Fredrick Peterson (Tame Developers)
PHP Ultimate Image Uploader Library 
```
|--------------------------------------------------------------------------
|This Library uses Image Compressor by can Bachors
|https://github.com/bachors/PHP-Image-Compressor-Class
```
[Github Link to Bachors Plugin](https://github.com/bachors/PHP-Image-Compressor-Class)

* [Requirements](#requirements)
* [Installation](#installation)
* [Instantiate](#instantiate)
* [Usage](#usage)
  * [Error Code](#error-code)
  * [INPUT HTML STRUCTURE](#input-html-structure)
  * [Use case defined](#use-case-defined)
  * [DIMENSION PARAM SIZE](#dimension-param-size)
  * [Get Image Width And Height](#get-image-width-and-height)
  * [Image AutoResize](#image-autoresize)
  * [Image WaterMark](#image-watermark)
  * [FOLDER Create](#folder-create)
  * [MIME TYPE](#mime-type)
  * [FINAL UPLOADED DATA](#final-upload-data)
* [Useful links](#useful-links)

## Requirements

- `>= php5.3.3+`

## Installation

Prior to installing `ultimate-uploader` get the [Composer](https://getcomposer.org) dependency manager for PHP because it'll simplify installation.

**Step 1** — update your `composer.json`:

```composer.json
"require": {
    "peterson/ultimate-uploader": "^1.2.0"
}
```

**Or composer install**:
```
composer require peterson/ultimate-uploader
```

**Step 2** — run [Composer](https://getcomposer.org):

```update
composer update
```


## Instantiate

**Step 1** — Composer  `Instantiate class using`:

```
require_once __DIR__ . '/vendor/autoload.php';

$upload = new ultimateUploader\ultimateUploader();

or
require_once __DIR__ . '/vendor/autoload.php';

use \ultimateUploader\ultimateUploader;

$upload = new ultimateUploader();
```

**Step 2** — PHP Direct  `Instantiate class using`:

```
include_once "pat_to/ultimateUploader.php";

$upload = new ultimateUploader\ultimateUploader();
```


```
You can download and entire repo and copy the src file alone to directory of your project.
- src/ultimateUploader.php

```

## Error Code
```
|--------------------------------------------------------------------------
| ALL Error Code
| Some error can be skipped, depending on what you need

-> ERROR_400 - no file upload 
-> ERROR_401 - select file to upload
-> ERROR_402 - File upload size is bigger than allowed size limit
-> ERROR_403 - Maximum file allowed exceeded 
-> ERROR_404 - Uploaded file format not allowed
-> ERROR_404 - Image size allowed error
-> ERROR_500 - Input file `name[]` must be passed as an array
| *************************************
```

**All Error code** — response property (public)  `->allError`:

```
$upload = new ultimateUploader\ultimateUploader();
echo $upload->allError;
```


**By default class has three (3) params on call (Instantiating)**

```
-> errorDisallowed -- index array [], all error code to ignore
-> base_dir -- Path to base dir (NULL on default)
-> base_url -- Path to base storage url (NULL on default)

$upload = new ultimateUploader\ultimateUploader([400, 401], 'path_to_dir', 'localhost/path_to_storage/folder');

- By default the error disallow array is empty
- By default the base dir, get path to your document root base directory
- By default the base url, get path to your base domain url/link
```

**We have seven (7) parameter when calling the run Method from the class**

```
-> fileUpload -- HTML input file name
-> folder_create -- Folder structure to use (Refer to Folder Create section for more detail)
-> upload_dir -- Directory to upload to (Do not pass in full path to folder)
-> type -- Mime Type to allow (Refer to MIME Types section for more detail)
-> size -- Maximum file size to allow (use any value of choice without conversion) i.e 2mb
-> limit_max -- Maximum upload limits
-> dimension_size -- For image dimension sizes (espects associative array). Please refer to DIMENSION PARAM ERROR
```

## INPUT HTML STRUCTURE
```
<input type="file" name="avatar[]">
```


## Use case defined

```
** -> We used Object Method Chaining **
** -> $upload->run()->error()->success(); **
** -> a new callable function is espected to be passed to "error Method and success Method" **
** -> pass any param `name` of choice on error and success function call **
```

- Plain Default setup
```
$upload->run('avatar', 'default', 'upload/avatar', 'images', '1.5mb', 1, ['height' => 500, 'width' => '300'])
    ->error(function($error){
        
		//you now have access to all public methods & properties using the $error var

    })->success(function($success){
		//you now have access to all public methods & properties using the $success var
    });
```

- Example 1
```
	//instantiate class
    $upload = new ultimateUploader\ultimateUploader();
	
$upload->run('avatar', 'year', 'upload/avatar', 'images', '1.5mb', 1)
    ->error(function($response){
        
        //error message
        echo $response->data['message'];
    })->success(function($response){

		On Successful uploads --- ERROR_200

        //run auto resize
        $response->imageAutoResize(200, 100, false);

        //run watermark
        $response->waterMark('watermark.png', '50', '100', false); //Add watermark automatically

        //run compression
        $response->compress(true); //will replace original to compressred -v


        //->data properties contains all uploaded info
        $response->data;
    });
```

- Example 2
```
	//instantiate class
    $upload = new ultimateUploader\ultimateUploader([400]);
    
    $upload->run('avatar', 'month', 'upload', 'images', '2.5mb', 2)
        ->error(function($response){

            //you can customize each error text message 
            if($response->data['status'] == 401){
                echo "Custom message - Please select a file to upload";
                return; //return key is used to stop further code exec on this function block only
            }
            
            //error message
            echo $response->data['message'];
        })->success(function($response){

            //proccess further code blocks
			$response->data;

        });
```

- Example 3
```
	//instantiate class
    $upload = new ultimateUploader\ultimateUploader([400]);
	
$upload->run('avatar', 'default', 'upload/avatar', 'images', '1.5mb', 1, ['height' => 500, 'width' => '300'])
    ->error(function($response){

		//you can customize each error text message 
		if($response->data['status'] == 401){
			echo "Custom message - Please select a file to upload";
		}
		elseif($response->data['status'] == 405){
			echo "CUSTOM MSG - {$response->data['message']}";
			return;
		}
		
		//error message
		echo $response->data['message'];
    })->success(function($response){

		//proccess further code blocks
		$response->data;
		
    });
```

### DIMENSION PARAM SIZE
```
Dimension size error check on $upload->run();

->  Takes an associative array --- ['width' => 500, 'height' => 700, 'same' => false]
->  By default same is set to `false` if not set.

$upload->run(['width' => 500, 'height' => 700])

['same' => false] => "Will only check if height or weight is greater or equal to allowed dimension set"
['same' => true] => "Will only check if height or weight is equal to allowed dimension set"

```

### Get Image Width And Height
```
Must be called before the  ->run Method for this to work
Useful for a single file upload like Cover Image or any other single upload

--- Takes just one param (HTML input file name)

//get file data
$width = $upload->getImageAttribute('path_to_img_file);


var_dump($width);


--- returns an assoc array

[
  ["height"]=> int(4209)
  ["width"]=> int(3368)
]

```


### Image AutoResize
```
By default Image Autoresize is set to -> false
You need to set to -> true in other to enable the method

-- Autoresize takes the lowest length value between Width & Height to crop/resize image

$upload->imageAutoResize(200, 100, false);
```


### Image WaterMark
```
By default Watermark is set to -> false
You need to set to -> true in other to enable the method

-- Watermark takes first param as path to image
i.e 'assets/image/watermark.png'

-- Second param is margin_right
-- third param if margin_bottom

$upload->waterMark('watermark.png', '50', '100', true);
```


### Image Compress
```
By default Compressor is set to -> false
You need to set to -> true in other to enable the method

Best practice for compressing is to make this the last callback method after the rest
->imageAutoResize
->waterMark
->compress

$upload->compress(true);
```


### FOLDER Create
```
By default Folder structure parameter is set to -> 'default'
-- Do not worry as folder do not need to exists before it can be created.
-- All uploaded files return all dataset of all uploads

-> 'default' Upload directory name/filename.jpg
-> 'year' Upload directory name/2022/filename.jpg
-> 'month' Upload directory name/2022/10/filename.jpg
-> 'day' Upload directory name/2022/10/28/filename.jpg

```
1. Default
   - Year
     - Month
     	- Day 
```
To use, pass in any of the below;
'default' | 'year' | 'month' | 'day' to the run method when calling

$upload->run('avatar', 'year', 'upload/avatar', 'images', '1.5mb', 1);

```


### MIME TYPE
```
'video'         =>  ['.mp4', '.mpeg', '.mov', '.avi', '.wmv'],
'audio'         =>  ['.mp3', '.wav'],
'files'         =>  ['.docx', '.pdf', '.txt'],
'images'        =>  ['.jpg', '.jpeg', '.png'],
'general_file'  =>  ['.docx', '.pdf', '.txt', '.zip', '.rar', '.xlsx', '.xls'],
'general_image' =>  ['.jpg', '.jpeg', '.png', '.webp'],
'general_media' =>  ['.mp3', '.wav', '.mp4', '.mpeg', '.mov', '.avi', '.wmv']

Pass in any of this into the Type parameter section when calling the ->run Method
```
- video
- audio
- files
- general_file
- images 
- general_image
- general_media


### FINAL UPLOADED DATA
```
Array
(
	[status] => 200
	[message] => avatar Uploaded successfully 
	[file] => Array
	(
	    [image] => Array
		(
		    [0] => path_to_uploaded_file.jpeg
		)

	    [new_image] => Array
		(
		    [0] => 164471880099d95c853c830f6.jpeg
		)

	    [folder] => Array
		(
		    [0] => upload/164471880099d95c853c830f6.jpeg
		)

	    [folder_real_path] => Array
		(
		    [0] => C:/xampp/htdocs/ultimateUploader-main/upload/164471880099d95c853c830f6.jpeg
		)

	    [folder_url] => Array
		(
		    [0] => http://localhost/ultimateUploader-main/upload/164471880099d95c853c830f6.jpeg
		)
	)
	[ext] => Array
	(
	    [0] => jpeg
	)
)
```

### Example Image
![Sample Image With Watermark](https://raw.githubusercontent.com/tamedevelopers/ultimateUploader/master/164471880099d95c853c830f6.jpeg)


### Example Image Original
```
Image size of 2.2mb
```
![Sample Original Image](https://raw.githubusercontent.com/tamedevelopers/ultimateUploader/master/collins-lesulie-0VEDrQXxrQo-unsplash.jpg)


### Example Image Original - Compressed -v
```
Image compressed to 988kb
Watermarked and retain its original quality
```
![Sample Original Compressed Image With Watermark](https://raw.githubusercontent.com/tamedevelopers/ultimateUploader/master/1644769592c599cf33805896d.jpg)



### Useful links

- If you love this PHP Library, you can [Buy Tame Developers a coffee](https://www.buymeacoffee.com/tamedevelopers)
- Link to Youtube Video Tutorial on usage will be available soon

