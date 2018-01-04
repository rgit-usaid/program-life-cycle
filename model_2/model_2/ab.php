<div style="padding:10px;">
	<!--<select class="form-control" ng-model="program_element_select"  ng-options="prgm_elem.program_element_name for prgm_elem in program_element_info track by prgm_elem.program_element_code"></select><br/>
	<input type="text" ng-model="search.program_element_code" class="form-control"/>
	<br/><label><input type="checkbox" ng-model="fontWeight" name="fontWeight"/> Bold</label><br/>-->
</div>
<table class="table table-bordered">
	<tr ng-repeat="prgm_elem in program_element_info | filter:{program_element_code:'PS'} | limitTo : 20 | orderBy: '-id' | filter:search" ng-class-odd="'odd'" ng-class-even="'even'" ng-class="{bold: fontWeight}">
		<td>{{prgm_elem.id | currency :'&#8360; ':0}}</td><td>{{prgm_elem.program_element_code}}</td><td>{{prgm_elem.program_element_name}}</td>
	</tr>
</table>