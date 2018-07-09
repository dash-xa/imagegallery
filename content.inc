<body>
<!-- 	image upload form -->
  <form action='index.php?form=true' id="contactform" method="post" enctype="multipart/form-data">
    <input type="reset" value="Reset" name="reset" class="clearfix btn btn-default"><br>
    <span class="error"><?php echo $errors["fname"]; ?></span>
    <h2>First Name: *</h2>
    <input type="text" name ="fname" value="<?php echo $fields["fname"]; ?>"> 
    <span class="error"><?php echo $errors["lname"];?></span>   
    <h2>Last Name: *</h2>
    <input type="text" name ="lname" value="<?php echo $fields["lname"]; ?>">
    <span class="error"><?php echo $errors["imageupload"]; ?></span>
    <h2>Upload file here * </h2>
    <br>
    <input type="file" name="imageupload" value="<?php echo $fields["imageupload"]; ?>" >
    <br><br>
    <span class="error"><?php echo $errors["description"]; ?></span>
    <h2>Description: </h2>
    <textarea rows="5" cols="50" name="description" class=""><?php echo $fields["description"]; ?></textarea>
    <br><br>
    <span class="error"><?php echo $errors["tags"]; ?></span>
    <h2>Tags:</h2>
    <textarea rows="5" cols="50" name="tags" class=""><?php echo $fields["tags"]; ?></textarea>
    <br><br>
    <span class="error"><?php echo $errors["copyright"]; ?></span>
    <br>
    <input type="checkbox" name="copyright" <?php if ($fields["copyright"]) echo "checked";?> value="true"> I retain the copyright to this image and release it for use on this site. 
    <br><br><br>
    <span class="error"><?php if(!($fields["access"] == "public" || $fields["access"] == "private")) echo $errors["access"]; ?></span>
    <br>
    <input type="radio" name="access" <?php if($fields["access"] == "public") echo "checked"; ?> value="public">Public
    <br>
    <input type="radio" name="access" <?php if($fields["access"] == "private") echo "checked"; ?> value="private">Private
    <br><br>
    <input type="submit" class="clearfix btn btn-default button">
  </form>