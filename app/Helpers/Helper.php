<?php

namespace App\Helpers;

if (!function_exists('human_file_size')) {
    /**
     * Returns a human readable file size
     *
     * @param integer $bytes
     * Bytes contains the size of the bytes to convert
     *
     * @param integer $decimals
     * Number of decimal places to be returned
     *
     * @return string a string in human readable format
     *
     * */
    function human_file_size($bytes, $decimals = 2)
    {
        $sz = 'BKMGTPE';
        $factor = (int)floor((strlen($bytes) - 1) / 3);
        return sprintf("%.{$decimals}f", $bytes / pow(1024, $factor)) . $sz[$factor];

    }
}

if (!function_exists('in_arrayi')) {

    /**
     * Checks if a value exists in an array in a case-insensitive manner
     *
     * @param mixed $needle
     * The searched value
     *
     * @param $haystack
     * The array
     *
     * @param bool $strict [optional]
     * If set to true type of needle will also be matched
     *
     * @return bool true if needle is found in the array,
     * false otherwise
     */
    function in_arrayi($needle, $haystack, $strict = false)
    {
        return in_array(strtolower($needle), array_map('strtolower', $haystack), $strict);
    }

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
                        Dropzone.autoDiscover       = true;
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
