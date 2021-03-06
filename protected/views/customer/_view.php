<?php
$this->widget('ToolbarButton',array('cssToolbar'=>'buttongrid','isEdit'=>true,'id'=>$model->addressbookid,
	'isDelete'=>true,'isDownload'=>true));
?>
<div id="tabledata">
<div class="rowdata">
	<span class="cell">ID</span>
    <span class="cell"><?php echo $model->addressbookid;?></span>
</div>
<div class="rowdata">
	<span class="cell">Customer</span>
    <span class="cell"><?php echo $model->fullname;?></span>
</div>
<div class="rowdata">
	<span class="cell">Status</span>
    <span class="cell"><?php echo ($model->recordstatus==1)?Catalogsys::model()->getcatalog("active"):Catalogsys::model()->getcatalog("notactive");?></span>
</div>
<div class="rowdata">
<span class="cell">Address</span>
<span class="cellcontent">
<div id='tabledetail'>
<?php $customeraddress = Customeraddress::model()->findallbyattributes(array('addressbookid'=>$model->addressbookid)); 
echo "<div class='rowheader'>";
 echo "<span class='celldetail'>Address Type</span>";
 echo "<span class='celldetail'>Address</span>";
 echo "<span class='celldetail'>City</span>";
 echo "<span class='celldetail'>Phone No</span>";
 echo "<span class='celldetail'>Fax No</span>";
 echo "</div>";

foreach ($customeraddress as $sa)
{
 echo "<div class='rowdetail'>";
 echo "<span class='celldetail'>".($sa->addresstype!==null)?$sa->addresstype->addresstypename:''."</span>";
 echo "<span class='celldetail'>".$sa->addressname."</span>";
 echo "<span class='celldetail'>".($sa->city!==null)?$sa->city->cityname:''."</span>";
 echo "<span class='celldetail'>".$sa->phoneno."</span>";
 echo "<span class='celldetail'>".$sa->faxno."</span>";
 echo "</div>";
}
?>
</div>
</span>
</div>
<div class="rowdata">
<span class="cell">Contact</span>
<span class="cell">
<div id='tabledetail'>
<?php $customeraddress = Customercontact::model()->findallbyattributes(array('addressbookid'=>$model->addressbookid)); 
echo "<div class='rowheader'>";
 echo "<span class='celldetail'>Contact Type</span>";
 echo "<span class='celldetail'>Contact</span>";
 echo "<span class='celldetail'>Phone No</span>";
 echo "<span class='celldetail'>Mobile Phone</span>";
 echo "<span class='celldetail'>Email</span>";
 echo "</div>";

foreach ($customeraddress as $sa)
{
 echo "<div class='rowdetail'>";
 echo "<span class='celldetail'>".($sa->contacttype!==null)?$sa->contacttype->contacttypename:''."</span>";
 echo "<span class='celldetail'>".$sa->addresscontactname."</span>";
 echo "<span class='celldetail'>".$sa->phoneno."</span>";
 echo "<span class='celldetail'>".$sa->mobilephone."</span>";
 echo "<span class='celldetail'>".$sa->emailaddress."</span>";
 echo "</div>";
}
?>
</div>
</span>
</div>
</div>

