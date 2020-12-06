<?php

namespace App\Classes;
use Session;
use Request;

class Helper {

    public static function dropzone( $name, $params = array(), $imagePath = '', $param_name = 'image', $multiple = false )
    {
        $id = '#' . $name;
        $imageField = '';
        $bgUrl = '';

        if( is_file($imagePath) ) {
            $bgUrl = 'background-image: url('.url($imagePath).');';
        }

        if( $multiple ) {
            $successCallbackJs = '
                var dzAttachment     = $("<div class=\"dz-attachment\"></div>");
                var removeAttachment = $("<i class=\"delete-icon fa fa-times\></i>");
                var inputAttachment = $("<input type=\"hidden\" name=\"'.$name.'[]\" />");
                removeAttachment.click(function(e) {
                    dzAttachment.remove();
                });
                dzAttachment.append(removeAttachment);
                dzAttachment.append(inputAttachment);
                dzAttachment.css({"background-image": "url(" + response.image_url + ")" });
                $("#'.$name.'").siblings(".multiple-attachments").append(dzAttachment);
                inputAttachment.val(response.image_path);
                ';
        } else {
            if( $param_name == 'file' ) {

                $successCallbackJs = '
                var __a = $("<a />").addClass("file-link").attr({ href: response.file_url }).text(response.file_path);
                $("#dropzone_inp_'.$name.'").val(response.file_path);
                __a.insertAfter("#dropzone_inp_'.$name.'");
                ';


            } else {

            $successCallbackJs = '
                $("#'.$name.'").css({"background-image": "url(" + response.image_url + ")" });
                $("#dropzone_inp_'.$name.'").val(response.image_path);
                ';

            }
            $fieldName = isset($params['field_name']) ? $params['field_name'] : $name;

            $imageField = '<input type="text" class="form-control" readonly name="'.$fieldName.'" id="dropzone_inp_'.$name.'" value="'.$imagePath.'" />';
        }

        return '<div class="dropzone-box" id="'.$name.'" style="'.$bgUrl.'">
                    <div class="dz-message" data-dz-message><span>Drag and Drop file(s) or Click to Upload</span></div>
                </div>
                '.$imageField.'
                <script>
                    jQuery(function($) {
                        Dropzone.autoDiscover       = false;
                        var _params                 = '.json_encode($params).';
                        _params.paramName           = "'.$param_name.'";
                        _params.addedfile           = function() {};
                        _params.dictDefaultMessage  = function() {};
                        _params.error               = function(err) {};
                        _params.uploadprogress      = function(file, progress) {};
                        _params.success             = function (file, response) {
                                                        if( response.status ) {
                                                            '.$successCallbackJs.'
                                                        }
                                                    };
                        _params.sending             = function (file, xhr, formData) {
                                                        formData.append("_token" , "'.csrf_token().'");
                                                    };

                        var dropzone = new Dropzone("'.$id.'" , _params );
                    });
                </script>';
    }
}
