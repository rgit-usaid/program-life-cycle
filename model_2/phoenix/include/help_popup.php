<?php
## Help array for add new===================
$add_help_arr = array();
$add_help_arr['fund']['title'] = "Help for Add New Fund";
$add_help_arr['fund']['help_description'] = "This is screen for how to add new Fund.
  You can select category first then enter fund name like(Development Assistance) in input field after you can give fund ID like (DA).Click to save button to add new fund after save this new fund will be show below list of all funds.This is screen for add new Fund.";

$add_help_arr['fund_strip']['title'] = "Help How May Add new Fund Strip";
$add_help_arr['fund_strip Strip']['help_description'] = "This is screen for add new Fund Strip.
  You can select Fund  first then choose fiscal year after fiscal year enter the amount, select Origination Point then save.";

$add_help_arr['allow_to_bureau']['title'] = "Help for allot fund to Bureau";
$add_help_arr['allow_to_bureau']['help_description'] = "Allot fresh fund from any Fund Strip + Program Element to Bureau. 
Once added, the fund will appear in list below.";

?>

<div class="fund-help-popup">
    <div class="row help-header">
        <div class="panel-heading col-sm-8 col-sm-offset-2"> <h4 class="text-center"><?php echo $add_help_arr[$page_name]['title'];?></h4></div>
        <div class=" col-sm-2 cancel-help text-right"><i class="fa fa-times pointer_help fa-2x" aria-hidden="true"></i></div>
    </div>
    <div class="content-section">
      <p><?php echo $add_help_arr[$page_name]['help_description'];?></p>
      </div>
</div>

<?php
### array for list help===================
$list_help_arr = array();
### data for fund====
$list_help_arr['fund']['title'] = "Help to Fund List";
$list_help_arr['fund']['help_description'] = "This is help for how to understand list data.This list showing all Fund name which is created.If you want to edit any Fund you can click on Edit button to edit Fund OR you can delete Fund by click on Delete button.";

### data for allow to bureau=======
$list_help_arr['allow_to_bureau']['title'] = "Help for allot to Bureau List ";
$list_help_arr['allow_to_bureau']['help_description'] = "The list shows grouped data based on Program Element & Bureau & fiscal year.
Each group shows Available and Allowed to OU columns. The group can be expanded by clicking down arrow to show break up of fund transactions.";

?>

<div class="fund-help-popup2">
    <div class="row help-header">
        <div class="panel-heading col-sm-8 col-sm-offset-2"> <h4 class="text-center"><?php echo $list_help_arr[$page_name]['title'];?></h4></div>
        <div class=" col-sm-2 cancel-help2 text-right"><i class="fa fa-times pointer_help2 fa-2x" aria-hidden="true"></i></div>
    </div>
    <div class="content-section">
      <p><?php echo $list_help_arr[$page_name]['help_description'];?></p>
    </div>
</div> 
 