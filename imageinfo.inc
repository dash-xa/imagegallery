<!-- Form showing the image info -->
<div class="col-lg-4 col-sm-6" id="formDiv">
<input type="hidden" name="imagename<?php echo $imagedata['imageupload']; ?>" value="<?php echo $imagedata['imageupload']; ?>">
<input type="checkbox" name="imageToDelete<?php echo $imagedata['imageupload']; ?>" id="cb<?php echo $src; ?>" value="<?php echo $imagedata['imageupload']; ?>"/> 
<label for="cb<?php echo $src; ?>"><img src="<?php echo $src; ?>"/></label>
<h2>First Name: *</h2>
<input type="text" name ="fname<?php echo $imagedata['imageupload']; ?>" value="<?php echo $imagedata['fname']; ?>"> 
<h2>Last Name: *</h2>
<input type="text" name ="lname<?php echo $imagedata['imageupload']; ?>" value="<?php echo $imagedata['lname']; ?>">
<h2>Description: </h2>
<textarea rows="5" cols="50" name="description<?php echo $imagedata['imageupload']; ?>"><?php echo $imagedata['description']; ?></textarea>
<h2>Tags:</h2>
<textarea rows="5" cols="50" name="tags<?php echo $imagedata['imageupload']; ?>"><?php echo $imagedata['tags']; ?></textarea>
</div>