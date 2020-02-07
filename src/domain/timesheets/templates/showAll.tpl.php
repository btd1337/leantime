<?php

defined( 'RESTRICTED' ) or die( 'Restricted access' );
$helper = $this->get('helper');
?>
<script type="text/javascript">
	
	jQuery(document).ready(function(){ 
		
	    jQuery("#checkAllEmpl").change(function(){	
	    	jQuery(".invoicedEmpl").prop('checked', jQuery(this).prop("checked"));
	    	if(jQuery(this).prop("checked") == true){
	    		jQuery(".invoicedEmpl").attr("checked", "checked");
	    		jQuery(".invoicedEmpl").parent().addClass("checked");
	    	}else{
	    		jQuery(".invoicedEmpl").removeAttr("checked");
	    		jQuery(".invoicedEmpl").parent().removeClass("checked");
	    	}
	    	
	    });
	    
	    jQuery("#checkAllComp").change(function(){
	    	jQuery(".invoicedComp").prop('checked', jQuery(this).prop("checked"));
	    	if(jQuery(this).prop("checked") == true){
	    		jQuery(".invoicedComp").attr("checked", "checked");
	    		jQuery(".invoicedComp").parent().addClass("checked");
	    	}else{
	    		jQuery(".invoicedComp").removeAttr("checked");
	    		jQuery(".invoicedComp").parent().removeClass("checked");
	    	}
	    });
	});		
        
    
</script>

<div class="pageheader">


    <div class="pageicon"><span class="iconfa-time"></span></div>
            <div class="pagetitle">
                <h5><?php $this->e($_SESSION['currentProjectClient']." // ". $_SESSION['currentProjectName']); ?></h5>
                <h1><?php echo $this->__("headline.project_timesheets") ?></h1>
            </div>
        </div><!--pageheader-->
        
        <div class="maincontent">
            <div class="maincontentinner">


<div id="loader">&nbsp;</div>
<form action="/timesheets/showAll" method="post" id="form" name="form">



<div class="headtitle" style="margin:0px; background: #eee;">
	<h4 class="widgettitle title-primary"><?php echo $this->__('label.filter'); ?></h4>
	<table class='table table-bordered' cellpadding="0" cellspacing="0" width="90%" id="">
	<thead>
		<tr id='toggleBody'>
			<th><label for="dateFrom"><?php echo $this->__('label.date_from'); ?></label></th>
			<th><label for="dateTo"><?php echo $this->__('label.date_to'); ?></label></th>
			<th><label></label></th>
			<th><label></label></th>
			<th></th>
		</tr>
	</thead>
	<tbody id='body'>
		<tr>
			<td><input type="text" id="dateFrom" class="dateFrom"  name="dateFrom"
				value="<?php echo $this->get('dateFrom'); ?>" size="7" /></td>
			<td><input type="text" id="dateTo" class="dateTo" name="dateTo"
				value="<?php echo $this->get('dateTo'); ?>" size="7" /></td>
			<td>
			<label for="userId"><?php echo $this->__("label.employee"); ?></label>
			<select name="userId" id="userId" onchange="submit();">
				<option value="all"><?php echo $this->__("label.all_employees"); ?></option>
	
				<?php foreach($this->get('employees') as $row) {
					echo'<option value="'.$row['id'].'"';
					if($row['id'] == $this->get('employeeFilter')) echo' selected="selected" ';
					echo'>'.$row['lastname'].', '.$row['firstname'].'</option>';
				}
	
				?>
			</select>
			<br />
			<label for="kind"><?php echo $this->__("label.type")?></label>
			<select id="kind" name="kind" onchange="submit();">
				<option value="all"><?php echo $this->__("label.all_types"); ?></option>
				<?php foreach($this->get('kind') as $row){
					echo'<option value="'.$row.'"';
					if($row == $this->get('actKind')) echo ' selected="selected"';
					echo'>'.$this->__($row).'</option>';
	
				}
				?>
	
			</select> </td>
			<td>
			<label for="invEmpl"><?php echo $this->__("label.invoiced"); ?></label>
			<input type="checkbox" value="on" name="invEmpl" id="invEmpl" onclick="submit();" 
				<?php 
				if($this->get('invEmpl') == '1') echo ' checked="checked"';
				?>
			/><br />
			<label for="invEmpl"><?php echo $this->__("label.invoiced_comp"); ?></label>
			<input type="checkbox" value="on" name="invComp" id="invComp" onclick="submit();" 
				<?php 
				if($this->get('invComp') == '1') echo ' checked="checked"';
				?>
			/>
			</td>
			<td>
				<input type="submit" value="<?php echo $this->__('buttons.search')?>" class="reload" />
			</td>
		</tr>
	</tbody>
</table>
<h4 class="widgettitle title-primary"><?php echo $this->__('headline.all_timesheets'); ?></h4>
</div>
<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered display" id="dyntableX">
	<colgroup>
      	  <col class="con0"/>
          <col class="con1" />
      	  <col class="con0"/>
          <col class="con1" />
      	  <col class="con0"/>
          <col class="con1" />
      	  <col class="con0"/>
          <col class="con1" />
      	  <col class="con0"/>
          <col class="con1" />
      	  <col class="con0"/>
          <col class="con1" />
      	  <col class="con0"/>
	</colgroup>
	<thead>
		<tr>
			<th><?php echo $this->__('label.date'); ?></th>
			<th><?php echo $this->__('label.hours'); ?></th>
			<th><?php echo $this->__('label.billable_hours'); ?></th>
			<th><?php echo $this->__('label.plan_hours'); ?></th>
			<th><?php echo $this->__('label.difference'); ?></th>
			<th><?php echo $this->__('label.ticket'); ?></th>
			<th><?php echo $this->__('label.project'); ?></th>
			<th><?php echo $this->__('label.employee'); ?></th>
			<th><?php echo $this->__("label.type")?></th>
			<th><?php echo $this->__('label.description'); ?></th>
			<th><?php echo $this->__('label.invoiced'); ?></th>
			<th><?php echo $this->__('label.invoiced_comp'); ?></th>
		</tr>
		<tr class='filter'>

			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
			<th></th>
		</tr>
	</thead>
	<tbody>

	<?php
	
	$sum = 0;
	$billableSum = 0;
	
	foreach($this->get('allTimesheets') as $row) {
		$sum = $sum + $row['hours'];?>
		<tr>

			<td><?php echo $helper->timestamp2date($row['workDate'], 2); ?></td>
			<td><?php echo $row['hours']; ?></td>
			<td>
			<?php 
				if ($row['kind'] != 'GENERAL_NOT_BILLABLE' && $row['kind'] != 'BUGFIXING_NOT_BILLABLE') {
					echo $row['hours'];
					$billableSum += $row['hours'];
				}
			?>
			</td>
			<td><?php echo $row['planHours']; ?></td>
			<?php $diff = $row['planHours']-$row['hours']; ?>
			<td <?php if($diff<0)echo'class="new" ';?>><?php echo $diff; ?></td>
			<td><a href="/tickets/showTicket/<?php echo $row['ticketId']; ?>"><?php echo $row['headline']; ?></a></td>
			<td><a href="/projects/showProject/<?php echo $row['projectId']; ?>"><?php echo $row['name']; ?></a></td>
			<td><?php echo $row['firstname']; ?>, <?php echo $row['lastname']; ?></td>
			<td><?php echo $this->__($row['kind']); ?></td>
			<td><?php echo $row['description']; ?></td>
			<td><?php if($row['invoicedEmpl'] == '1'){?> <?php echo $helper->timestamp2date($row['invoicedEmplDate'], 2); ?>
			<?php }else{ ?> <input type="checkbox" name="invoicedEmpl[]" class="invoicedEmpl"
				value="<?php echo $row['id']; ?>" /> <?php } ?></td>
			<td><?php if($row['invoicedComp'] == '1'){?> <?php echo $helper->timestamp2date($row['invoicedCompDate'], 2); ?>
			<?php }else{ ?> <input type="checkbox" name="invoicedComp[]" class="invoicedComp"
				value="<?php echo $row['id']; ?>" /> <?php } ?></td>
		</tr>
		<?php } ?>
		<?php if(count($this->get('allTimesheets')) === 0){ ?>
		<tr>
			<td colspan="13"><?php echo $this->__("label.no_results"); ?></td>
		</tr>

		<?php } ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="1"><strong><?php echo $this->__("label.total_hours")?></strong></td>
			<td colspan="1"><strong><?php echo $sum; ?></strong></td>
			<td colspan="7"><strong><?php echo $billableSum; ?></strong></td>
			<td>
				<input type="submit" class="button" value="<?php echo $this->__('buttons.save'); ?>" name="saveInvoice" />
			</td>
			<td><input type="checkbox" id="checkAllEmpl" /><?php echo $this->__('label.select_all')?></td>
			<td><input type="checkbox"  id="checkAllComp" /><?php echo $this->__('label.select_all')?></td>
		</tr>
	</tfoot>
</table>

</form>


<script type='text/javascript'>
	jQuery(document).ready(function() {
		jQuery('#toggleBody').click(function() {
			jQuery('#body').toggle();	
		});
	});
</script>

			</div>
		</div>