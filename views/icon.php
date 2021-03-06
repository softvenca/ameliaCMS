<?php $_SESSION["title"] = icon ?>
<div class="box">
	<div class="box-tools">
		<div class="box-tool-left">
			<a href="<?=url_base?>home/dashboard"><?=dashboard?></a> <i class="fa fa-angle-right"></i> <a href="<?=url_base.routerCtrl?>"><?=icon?></a> <?=(action!="index")? "<i class='fa fa-angle-right'></i> ".((action=="add")? add : ((action=="edit")? edit : query ) ) : ''?>
		</div>
		<div class="box-tool-right"><i class="glyphicon glyphicon-minus"></i></div>
	</div>
	<div class="box-container">
		<?php if(action=="index"){ ?>
			<?=$dependencies['add']?>
			<table id="datatable" class="table table-striped table-bordered table-hover dataTable">
                <thead><th><?=id?></th><th><?=icon_class?></th><th><?=icon_name?></th><th><?=actions?></th></thead>
                <tfoot><th><?=id?></th><th><?=icon_class?></th><th><?=icon_name?></th><th><?=actions?></th></tfoot>
            </table>
            <script>
	            $(document).ready( function () {
	                $('#datatable').dataTable(
		                {
		                	"language":{
		                    	"url": "<?=url_base?>third_party/datatables/language/es.json"
		                        },
	                        "processing": true,
	                        "serverSide": true,
	                        "ordering": false,
	                        "ajax": { url : "<?=url_base.routerCtrl?>/listt", type : "POST" },
	                        "columns": [
	                            { "data": "idicon" },
	                            { "data": "class" },
	                            { "data": "name" },
	                            { "data": "btn" }
	                        ],
	                    },
	                );
	            });
	        </script>
		<?php }else{ ?>
			<?=(action!="query")? "<form action='".url_base.routerCtrl."/".action."/".$d["idicon"]."' method='POST' class='form-horizontal'>" : "<div class='form-horizontal'>" ?>
				<input type="hidden" name="event" id="event">
				<?php
					if(action!="add")
						echo "<div class='form-group'>
							<label class='col-md-2 text-right'>".id.":</label>
							<div class='col-md-3'>
								<input type='text' name='idicon' id='idicon' value='".$d["idicon"]."' class='width-full' disabled title='".id_title."'>
							</div>
						</div>";
				?>
				<div class="form-group">
					<label class="col-md-2 text-right"><?=icon_class?>:</label>
					<div class="col-md-3">
						<input type="text" name="class" id="class" value="<?=$d["class"]?>" aajs="required" class="width-full" <?=(action=="query")?'disabled':''?> data-toggle="tooltip" title="<?=icon_class_title?>" placeholder="<?=icon_class_placeholder?>">
					</div>
				</div>
				<div class="form-group">
					<label class="col-md-2 text-right"><?=icon_name?>:</label>
					<div class="col-md-3">
						<input type="text" name="name" id="name" value="<?=$d["name"]?>" aajs="required" class="width-full" <?=(action=="query")?'disabled':''?> data-toggle="tooltip" title="<?=icon_name_title?>" placeholder="<?=icon_name_placeholder?>">
					</div>
				</div>
				<?php
					if(action!="query")
						echo"<div class='form-group'>
							<div class='col-md-2 col-md-offset-5'>
								<button class='btn1' aajs='send'>".save."</button>
							</div>
						</div>";
				?>
			<?=(action!="query")? "</form>" :'</div>' ?>
		<?php } ?>
	</div>
</div>