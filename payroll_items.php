<?php include('db_connect.php') ?>
<?php
		$pay = $conn->query("SELECT * FROM payroll where id = ".$_GET['id'])->fetch_array();
		$pt = array(1=>"Monhtly",2=>"Semi-Monthly");
?>
		<div class="container-fluid " >
			<div class="col-lg-12">
				
				<br />
				<br />
				<div class="card">
					<div class="card-header">
						<span><b>Nómina : <?php echo $pay['ref_no'] ?></b></span>
						
						<button class="btn btn-primary btn-sm btn-block col-md-2 float-right" type="button" id="new_payroll_btn"><span class="fa fa-plus"></span> Re-Calcular nómina</button>
					</div>
					<div class="card-body">
						<div class="row">
							<div class="col-md-12">
							<p>Fecha nómina: <b><?php echo date("M d, Y",strtotime($pay['date_from'])). " - ".date("M d, Y",strtotime($pay['date_to'])) ?></b></p>
							<p>Tipo de pago: <b><?php echo $pt[$pay['type']] ?></b></p>
							<button class="btn btn-success btn-sm btn-block col-md-2 float-right" type="button" id="print_btn"><span class="fa fa-print"></span> Imprimir</button>
							</div>
						</div>
						<hr>
						<table id="table" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>ID empleado</th>
									<th>Nombre</th>
									<th>Dias de faltas</th>
									<th>Llegadas tarde</th>
									<th>Total Novedades</th>
									<th>Total Deducciones</th>
									<th>Pago neto</th>
									<th>Accion</th>
								</tr>
							</thead>
							<tbody>
								<?php
									
									$payroll=$conn->query("SELECT p.*,concat(e.lastname,', ',e.firstname,' ',e.middlename) as ename,e.employee_no FROM payroll_items p inner join employee e on e.id = p.employee_id ") or die(mysqli_error());
									while($row=$payroll->fetch_array()){
								?>
								<tr>
									<td><?php echo $row['employee_no'] ?></td>
									<td><?php echo ucwords($row['ename']) ?></td>
									<td><?php echo $row['absent'] ?></td>
									<td><?php echo $row['late'] ?></td>
									<td><?php echo number_format($row['allowance_amount'],2) ?></td>
									<td><?php echo number_format($row['deduction_amount'],2) ?></td>
									<td><?php echo number_format($row['net'],2) ?></td>
									<td>
										<center>
									
										 <button class="btn btn-sm btn-outline-primary view_payroll" data-id="<?php echo $row['id']?>" type="button"><i class="fa fa-eye"></i> Ver</button>
									
										</center>
									</td>
								</tr>
								<?php
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
			
		
		
	<script type="text/javascript">
		$(document).ready(function(){
			$('#table').DataTable();
		});
	</script>
	<script type="text/javascript">
		$(document).ready(function(){

			

			$('#print_btn').click(function(){
				var nw = window.open("print_payroll.php?id=<?php echo $_GET['id'] ?>","_blank","height=500,width=800")
				setTimeout(function(){
					nw.print()
					setTimeout(function(){
						nw.close()
						},500)
				},1000)
			})

			
			
			$('.view_payroll').click(function(){
				var $id=$(this).attr('data-id');
				uni_modal("Desprendible de pago","view_payslip.php?id="+$id,"large")
				
			});
			
			$('#new_payroll_btn').click(function(){
				start_load()
				$.ajax({
					url:'ajax.php?action=calculate_payroll',
					method:"POST",
					data:{id:'<?php echo $_GET['id'] ?>'},
					error:err=>console.log(err),
					success:function(resp){
							if(resp == 1){
								alert_toast("Nómina exitosamente calculada","success");
									setTimeout(function(){
									location.reload();

								},1000)
							}
						}
				})
			})
		});
		function remove_payroll(id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_payroll',
				method:"POST",
				data:{id:id},
				error:err=>console.log(err),
				success:function(resp){
						if(resp == 1){
							alert_toast("Dato de empleado exitosamente eliminado","success");
								setTimeout(function(){
								location.reload();

							},1000)
						}
					}
			})
		}
	</script>
