<a data-fancybox="image" href="<?php echo IMAGES.$imagedata['imageupload']; ?>" data-caption="Creator: 
  <?php echo $imagedata['fname'].' '.$imagedata['lname']; ?><br>
  Description: <?php echo $imagedata['description']; ?><br>
  Tags: <?php echo $imagedata['tags']; ?><br>
  <?php 
    if(isset($_SESSION['isEditor']) && $_SESSION['isEditor'] == true)
    	echo '<a href=\''.IMAGES.$imagedata["imageupload"].'\' download=\'\'>Download</a>';
    ?>
  ">
<img src="<?php echo $src; ?>" alt="Image"></a>