<?php $this->load->view('common_files/header.php'); ?>

<div class="container">
    <h1 class="text-center"><?= $this->TITLE ?> User</h1>
    <div class="row">
        <form style="width: 100%" name="userForm" id="userForm" method="post" action="<?= base_url('crud/save'); ?>">
            <input type="hidden" name="userId" id="userId"
                   value="<?= isset($userData->user_id) ? $userData->user_id : '' ?>">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter username"
                       value="<?= isset($userData->username) ? $userData->username : '' ?>">
            </div>
            <div class="form-group">
                <label for="company_name">Company Name</label>
                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="Company Name"
                       value="<?= isset($userData->company_name) ? $userData->company_name : '' ?>">
            </div>
            <div class="form-group" style="position:relative;">
                <button class="add-location-btn" id="add-location">Add Location</button>
                <label>Location</label>
                <?php
                if (isset($userData->company_location)) {
                    $locationCounter = 1;
                    $decodedLocation = json_decode($userData->company_location);
                    foreach ($decodedLocation as $locationRow) {
                        echo '<input type="text" class="form-control location-input" id="location-' . $locationCounter . '" name="location[]"
                               value="' . $locationRow . '" placeholder="Location">';
                        $locationCounter++;
                    }
                } else {
                    ?>
                    <input type="text" class="form-control location-input" id="location-1" name="location[]"
                           placeholder="Location">
                    <?php
                }
                ?>

            </div>
            <hr/>
            <div class="form-group">
                <?php
                $rolesArr = array('admin', 'editor', 'publisher');
                if (isset($userData->roles)) {
                    $decodedRoles = json_decode($userData->roles);
                    foreach ($rolesArr as $role) {
                        ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="<?= $role ?>"
                                   value="<?= $role ?>" <?= in_array($role, $decodedRoles) ? "checked" : "" ?> >
                            <label class="form-check-label chk-roles" for="<?= $role ?>"><?= $role ?></label>
                        </div>
                        <?php
                    }
                } else {
                    foreach ($rolesArr as $role) {
                        ?>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" name="roles[]" id="<?= $role ?>"
                                   value="<?= $role ?>">
                            <label class="form-check-label chk-roles" for="<?= $role ?>"><?= $role ?></label>
                        </div>
                        <?php
                    }
                }
                ?>
            </div>
            <hr/>
            <div class="form-group">
                <label for="userImages">Images ( You can insert multiple images )</label>
                <input type="file" class="form-control-file" id="userImages" name="userImages" multiple>
            </div>
            <div class="form-group" id="imagesPreviewDiv">
                <?php
                    if(isset($userImages)){
                        $counter =1;
                        foreach($userImages as $row)
                        {
                            $img=base_url().$row['image'];
                            ?>
                            <div class="img-box galleryImg" id="GI<?= $counter ?>" style="background-image: url('<?= $img; ?>')">
                                <button type="button" class="delete_button" onclick="updateImage(<?= $row['user_image_id'];?>,<?= $counter?>)">
                                    <i class="fa fa-trash-o" aria-hidden="true"></i>
                                </button>
                            </div>
                            <?php
                            $counter++;
                        }
                    }
                ?>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>


<?php $this->load->view('common_files/footer'); ?>
<script>
    $(document).ready(function () {
        jQuery(function ($) {
            var ci = {
                init: function () {
                    //add location on clicking "Add Location"
                    $('#add-location').on('click', this.addLocation);
                    //preview images on selecting files
                    $("#userImages").on('change', this.previewImages);
                },

                addLocation: function (e) {
                    e.preventDefault();
                    var locationCount = $('.location-input').length;
                    var html = '<input type="text" class="form-control location-input" id="location-' + (locationCount + 1) + '" name="location[]" placeholder="Location" style="margin-top: 10px">';
                    $("#location-" + locationCount).after(html);
                },

                previewImages: function (e) {

                    var form_data = new FormData();
                    var ins = document.getElementById('userImages').files.length;
                    for (var x = 0; x < ins; x++) {
                        form_data.append("files[]", document.getElementById('userImages').files[x]);
                    }
                    $.ajax({
                        url: BASE_URL + "crud/addTempImages",
                        type: "POST",
                        data: form_data,
                        contentType: false,
                        cache: false,
                        processData:false,
                        success: function(data)
                        {
                            // location.reload();
                            // $("#galleryDiv").load(location.href + " #galleryDiv");
                        }
                    });

                    var fileList = this.files;
                    var anyWindow = window.URL || window.webkitURL;
                    var numItems = $('.galleryImg').length;
                    for(var i = 0; i < fileList.length; i++){
                    	var objectUrl = anyWindow.createObjectURL(fileList[i]);
                    	$('#imagesPreviewDiv').append('<div class="img-box galleryImg" id="GI'+(numItems+i) +'" style="background-image: url('+ objectUrl +')"><button type="button" class="delete_button" onclick="deleteCarGallery('+ (numItems+i) +')"><i class="fa fa-trash-o" aria-hidden="true"></i></button></div>');
                    	window.URL.revokeObjectURL(fileList[i]);
                    }
                }

            }
            ci.init();
        });
    });
</script>