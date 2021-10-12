<?php include 'db_connect.php' ?>
<?php 


?>
<div class="container-fluid">
	<div class="col-lg-12">
		<form id="manage-payroll">
				<input type="hidden" name="id" value="">
				<div class="form-group">
					<label for="" class="control-label">Fecha comienzo :</label>
					<input type="date" class="form-control" name="date_from">
				</div>
				<div class="form-group">
					<label for="" class="control-label">Fecha final :</label>
					<input type="date" class="form-control" name="date_to">
				</div>
				<div class="form-group">
					<label for="" class="control-label">Tipo de pago :</label>
					<select name="type" class="custom-select browser-default" id="">
						<option value="1">Mensual</option>
						<option value="2">Quincenal</option>
					</select>
				</div>
		</form>
	</div>
</div>

<script>
	$('#manage-payroll').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
		url:'ajax.php?action=save_payroll',
		method:"POST",
		data:$(this).serialize(),
		error:err=>console.log(),
		success:function(resp){
				if(resp == 1){
					alert_toast("Nómina guardada correctamente","success");
					setTimeout(function(){
								location.reload()
							},1000)
				}
		}
	})
	})
</script>