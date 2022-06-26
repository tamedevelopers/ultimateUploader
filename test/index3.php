<?php

include_once "../src/ultimateUploader.php";

//instantiate class
$upload = new ultimateUploader\ultimateUploader([400]);

$upload->run('avatar', 'default', 'upload', 'images', '2.5mb', 2, ['width' => '3368'])
    ->error(function($response){

        //you can change each error text message yourself
        if($response->data['status'] == 405){
            echo "CUSTOM MSG - {$response->data['message']}";
            return;
        }
        
        //error message
        echo $response->data['message'];
    })->success(function($response){
        
        //run auto resize
        $response->imageAutoResize(200, 100, false);

        //run watermark
        $response->waterMark('watermark.png', '50', '100', true); //Add watermark automatically

        //run compression
        $response->compress(true); //will replace original to compressred -v

        //$response->data - will get all succuessful image data upload 

        var_dump($response->data);
    });

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <center>
        <form method="post" enctype="multipart/form-data">
            
                <h3 class="valign-wrapper prod_hding_main mb-3">Upload file</h3>
                
                <!--file upload-->
                <div class="col-sm-12 mt-3">
                    <div class="form-group">
                        <label for="upload">Image</label>
                        <input type="file" class="form-control-file" id="upload" 
                                name="avatar[]" multiple>
                    </div>
                </div>

                <button type="submit" style="margin-top: 40px;">
                    Upload File
                </button>
            
        </form>
    </center>
</body>
</html>