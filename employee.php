<?php include('db_connect.php') ?>
		<div class="container-fluid " >
			<div class="col-lg-12">
				
				<br />
				<br />
				<div class="card">
					<div class="card-header">
						<span><b>Lista de empleados</b></span>
						<button class="btn btn-primary btn-sm btn-block col-md-3 float-right" type="button" id="new_emp_btn"><span class="fa fa-plus"></span> Añadir empleado</button>
					</div>
					<div class="card-body">
						<table id="table" class="table table-bordered table-striped">
							<thead>
								<tr>
									<th>Id empelado</th>
									<th>Primer nombre</th>
									<th>Segundo nombre</th>
									<th>Apellido</th>
									<th>Proyecto</th>
									<th>Cargo</th>
									<th>Accion</th>
								</tr>
							</thead>
							<tbody>
								<?php
									$d_arr[0] = "Unset";
									$p_arr[0] = "Unset";
									$dept = $conn->query("SELECT * from department order by name asc");
										while($row=$dept->fetch_assoc()):
											$d_arr[$row['id']] = $row['name'];
										endwhile;
										$pos = $conn->query("SELECT * from position order by name asc");
										while($row=$pos->fetch_assoc()):
											$p_arr[$row['id']] = $row['name'];
										endwhile;
									$employee_qry=$conn->query("SELECT * FROM employee") or die(mysqli_error());
									while($row=$employee_qry->fetch_array()){
								?>
								<tr>
									<td><?php echo $row['employee_no']?></td>
									<td><?php echo $row['firstname']?></td>
									<td><?php echo $row['middlename']?></td>
									<td><?php echo $row['lastname']?></td>
									<td><?php echo $d_arr[$row['department_id']]?></td>
									<td><?php echo $p_arr[$row['position_id']]?></td>
									<td>
										<center>
										 <button class="btn btn-sm btn-outline-primary view_employee" data-id="<?php echo $row['id']?>" type="button"><i class="fa fa-eye"></i></button>
										 <button class="btn btn-sm btn-outline-primary edit_employee" data-id="<?php echo $row['id']?>" type="button"><i class="fa fa-edit"></i></button>
										<button class="btn btn-sm btn-outline-danger remove_employee" data-id="<?php echo $row['id']?>" type="button"><i class="fa fa-trash"></i></button>
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

			

			
			$('.edit_employee').click(function(){
				var $id=$(this).attr('data-id');
				uni_modal("Editar empleado","manage_employee.php?id="+$id)
				
			});
			$('.view_employee').click(function(){
				var $id=$(this).attr('data-id');
				uni_modal("Detalles del empleado","view_employee.php?id="+$id,"mid-large")
				
			});
			$('#new_emp_btn').click(function(){
				uni_modal("Nuevo empleado","manage_employee.php")
			})
			$('.remove_employee').click(function(){
				_conf("¿Está seguro de eliminar a este empleado?","remove_employee",[$(this).attr('data-id')])
			})
		});
		function remove_employee(id){
			start_load()
			$.ajax({
				url:'ajax.php?action=delete_employee',
				method:"POST",
				data:{id:id},
				error:err=>console.log(err),
				success:function(resp){
						if(resp == 1){
							alert_toast("Los datos del empleado se eliminaron correctamente",'success');
								setTimeout(function(){
								location.reload();

							},1000)
						}
					}
			})
		}
	</script>
