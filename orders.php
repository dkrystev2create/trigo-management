<?php 
require_once 'php_action/db_connect.php'; 
require_once 'includes/header.php'; 

if($_GET['o'] == 'add') { 
// add order
	echo "<div class='div-request div-hide'>add</div>";
} else if($_GET['o'] == 'manord') { 
	echo "<div class='div-request div-hide'>manord</div>";
} else if($_GET['o'] == 'editOrd') { 
	echo "<div class='div-request div-hide'>editOrd</div>";
} // /else manage order


?>

<ol class="breadcrumb">
	<li><a href="dashboard.php">Начална страница</a></li>
	<li>Поръчки</li>
	<li class="active">
		<?php if($_GET['o'] == 'add') { ?>
		Добави поръчка
		<?php } else if($_GET['o'] == 'manord') { ?>
		Виж поръчки
		<?php } // /else manage order ?>
	</li>
</ol>


<h4>
	<i class='glyphicon glyphicon-circle-arrow-right'></i>
	<?php if($_GET['o'] == 'add') {
		echo "Добави поръчка";
	} else if($_GET['o'] == 'manord') { 
		echo "Виж поръчка";
	} else if($_GET['o'] == 'editOrd') { 
		echo "Промени поръчка";
	}
	?>	
</h4>



<div class="panel panel-default">
	<div class="panel-heading">

		<?php if($_GET['o'] == 'add') { ?>
		<i class="glyphicon glyphicon-plus-sign"></i> Добави поръчка
		<?php } else if($_GET['o'] == 'manord') { ?>
		<i class="glyphicon glyphicon-edit"></i> Виж поръчка
		<?php } else if($_GET['o'] == 'editOrd') { ?>
		<i class="glyphicon glyphicon-edit"></i> Промени поръчка
		<?php } ?>

	</div> <!--/panel-->	
	<div class="panel-body">

		<?php if($_GET['o'] == 'add') { 
			// add order
			?>			

			<div class="success-messages"></div> <!--/success-messages-->

			<form class="form-horizontal" method="POST" action="php_action/createOrder.php" id="createOrderForm">

				<div class="form-group">
					<label for="orderDate" class="col-sm-2 control-label">Дата</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" />
					</div>
				</div> <!--/form-group-->
				<div class="form-group">
					<label for="clientName" class="col-sm-2 control-label">Име на клиента</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="clientName" name="clientName" placeholder="Име на клиент" autocomplete="off" />
					</div>
				</div> <!--/form-group-->
				<div class="form-group">
					<label for="clientContact" class="col-sm-2 control-label">Телефон за връзка</label>
					<div class="col-sm-10">
						<input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Телефон" autocomplete="off" />
					</div>
				</div> <!--/form-group-->			  

				<table class="table" id="productTable">
					<thead>
						<tr>			  			
							<th style="width:40%;">Продукт</th>
							<th style="width:20%;">Цена</th>
							<th style="width:15%;">Количество</th>			  			
							<th style="width:15%;">Общо</th>			  			
							<th style="width:10%;"></th>
						</tr>
					</thead>
					<tbody>
						<?php
						$arrayNumber = 0;
						for($x = 1; $x < 4; $x++) { ?>
						<tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">			  				
							<td style="margin-left:20px;">
								<div class="form-group">

									<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" >
										<option value="">~~Избери~~</option>
										<?php
										$productSql = "SELECT * FROM product WHERE active = 1 AND status = 1 AND quantity != 0";
										$productData = $connect->query($productSql);

										while($row = $productData->fetch_array()) {									 		
											echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."'>".$row['product_name']."</option>";
										 	} // /while 

										 	?>
										 </select>
										</div>
									</td>
									<td style="padding-left:20px;">			  					
										<input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" />			  					
										<input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" />			  					
									</td>
									<td style="padding-left:20px;">
										<div class="form-group">
											<input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" />
										</div>
									</td>
									<td style="padding-left:20px;">			  					
										<input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" />			  					
										<input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" />			  					
									</td>
									<td>

										<button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
									</td>
								</tr>
								<?php
								$arrayNumber++;
			  		} // /for
			  		?>
			  	</tbody>			  	
			  </table>

			  <div class="col-md-6">
			  	<div class="form-group">
			  		<label for="subTotal" class="col-sm-3 control-label">Цена без ДДС</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" />
			  			<input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="vat" class="col-sm-3 control-label">ДДС 20%</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="vat" name="vat" disabled="true" />
			  			<input type="hidden" class="form-control" id="vatValue" name="vatValue" />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="totalAmount" class="col-sm-3 control-label">Цена с ДДС</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true"/>
			  			<input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="discount" class="col-sm-3 control-label">Отстъпка(Т.О)</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" />
			  		</div>
			  	</div> <!--/form-group-->	
			  	<div class="form-group">
			  		<label for="grandTotal" class="col-sm-3 control-label">Обща цена</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" />
			  			<input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" />
			  		</div>
			  	</div> <!--/form-group-->			  		  
			  </div> <!--/col-md-6-->

			  <div class="col-md-6">
			  	<div class="form-group">
			  		<label for="paid" class="col-sm-3 control-label">Платено</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="due" class="col-sm-3 control-label">За плащане</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="due" name="due" disabled="true" />
			  			<input type="hidden" class="form-control" id="dueValue" name="dueValue" />
			  		</div>
			  	</div> <!--/form-group-->		
			  	<div class="form-group">
			  		<label for="clientContact" class="col-sm-3 control-label">Тип плащане</label>
			  		<div class="col-sm-9">
			  			<select class="form-control" name="paymentType" id="paymentType">
			  				<option value="">~~Избери~~</option>
			  				<option value="1">Чек</option>
			  				<option value="2">Кеш</option>
			  				<option value="3">Карта</option>
			  			</select>
			  		</div>
			  	</div> <!--/form-group-->							  
			  	<div class="form-group">
			  		<label for="clientContact" class="col-sm-3 control-label">Статус плащане</label>
			  		<div class="col-sm-9">
			  			<select class="form-control" name="paymentStatus" id="paymentStatus">
			  				<option value="">~~Избери~~</option>
			  				<option value="1">В пълен размер</option>
			  				<option value="2">Аванс от 50%</option>
			  				<option value="3">Безплатно</option>
			  			</select>
			  		</div>
			  	</div> <!--/form-group-->							  
			  </div> <!--/col-md-6-->


			  <div class="form-group submitButtonFooter">
			  	<div class="col-sm-offset-2 col-sm-10">
			  		<button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Добави ред</button>

			  		<button type="submit" id="createOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Готово!</button>

			  		<button type="reset" class="btn btn-default" onclick="resetOrderForm()"><i class="glyphicon glyphicon-erase"></i> Изчисти</button>
			  	</div>
			  </div>
			</form>
			<?php } else if($_GET['o'] == 'manord') { 
			// manage order
				?>

				<div id="success-messages"></div>

				<table class="table" id="manageOrderTable">
					<thead>
						<tr>
							<th>№</th>
							<th>Дата</th>
							<th>Име на клиент</th>
							<th>Телефон за връзка</th>
							<th>Общо поръчани</th>
							<th>Статус на плащане</th>
							<th>Опции</th>
						</tr>
					</thead>
				</table>

				<?php 
		// /else manage order
			} else if($_GET['o'] == 'editOrd') {
			// get order
				?>

				<div class="success-messages"></div> <!--/success-messages-->

				<form class="form-horizontal" method="POST" action="php_action/editOrder.php" id="editOrderForm">

					<?php $orderId = $_GET['i'];

					$sql = "SELECT orders.order_id, orders.order_date, orders.client_name, orders.client_contact, orders.sub_total, orders.vat, orders.total_amount, orders.discount, orders.grand_total, orders.paid, orders.due, orders.payment_type, orders.payment_status FROM orders 	
					WHERE orders.order_id = {$orderId}";

					$result = $connect->query($sql);
					$data = $result->fetch_row();				
					?>

					<div class="form-group">
						<label for="orderDate" class="col-sm-2 control-label">Дата</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="orderDate" name="orderDate" autocomplete="off" value="<?php echo $data[1] ?>" />
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="clientName" class="col-sm-2 control-label">Име на клиента</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="clientName" name="clientName" placeholder="Client Name" autocomplete="off" value="<?php echo $data[2] ?>" />
						</div>
					</div> <!--/form-group-->
					<div class="form-group">
						<label for="clientContact" class="col-sm-2 control-label">Телефон за връзка</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" id="clientContact" name="clientContact" placeholder="Contact Number" autocomplete="off" value="<?php echo $data[3] ?>" />
						</div>
					</div> <!--/form-group-->			  

					<table class="table" id="productTable">
						<thead>
							<tr>			  			
								<th style="width:40%;">Продукт</th>
								<th style="width:20%;">Цена</th>
								<th style="width:15%;">Количество</th>			  			
								<th style="width:15%;">Общо</th>			  			
								<th style="width:10%;"></th>
							</tr>
						</thead>
						<tbody>
							<?php

							$orderItemSql = "SELECT order_item.order_item_id, order_item.order_id, order_item.product_id, order_item.quantity, order_item.rate, order_item.total FROM order_item WHERE order_item.order_id = {$orderId}";
							$orderItemResult = $connect->query($orderItemSql);
						// $orderItemData = $orderItemResult->fetch_all();						

						// print_r($orderItemData);
							$arrayNumber = 0;
			  		// for($x = 1; $x <= count($orderItemData); $x++) {
							$x = 1;
							while($orderItemData = $orderItemResult->fetch_array()) { 
			  			// print_r($orderItemData); ?>
			  			<tr id="row<?php echo $x; ?>" class="<?php echo $arrayNumber; ?>">			  				
			  				<td style="margin-left:20px;">
			  					<div class="form-group">

			  						<select class="form-control" name="productName[]" id="productName<?php echo $x; ?>" onchange="getProductData(<?php echo $x; ?>)" >
			  							<option value="">~~Избери~~</option>
			  							<?php
			  							$productSql = "SELECT * FROM product WHERE active = 1 AND status = 1 AND quantity != 0";
			  							$productData = $connect->query($productSql);

			  							while($row = $productData->fetch_array()) {									 		
			  								$selected = "";
			  								if($row['product_id'] == $orderItemData['product_id']) {
			  									$selected = "selected";
			  								} else {
			  									$selected = "";
			  								}

			  								echo "<option value='".$row['product_id']."' id='changeProduct".$row['product_id']."' ".$selected." >".$row['product_name']."</option>";
										 	} // /while 

										 	?>
										 </select>
										</div>
									</td>
									<td style="padding-left:20px;">			  					
										<input type="text" name="rate[]" id="rate<?php echo $x; ?>" autocomplete="off" disabled="true" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />			  					
										<input type="hidden" name="rateValue[]" id="rateValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['rate']; ?>" />			  					
									</td>
									<td style="padding-left:20px;">
										<div class="form-group">
											<input type="number" name="quantity[]" id="quantity<?php echo $x; ?>" onkeyup="getTotal(<?php echo $x ?>)" autocomplete="off" class="form-control" min="1" value="<?php echo $orderItemData['quantity']; ?>" />
										</div>
									</td>
									<td style="padding-left:20px;">			  					
										<input type="text" name="total[]" id="total<?php echo $x; ?>" autocomplete="off" class="form-control" disabled="true" value="<?php echo $orderItemData['total']; ?>"/>			  					
										<input type="hidden" name="totalValue[]" id="totalValue<?php echo $x; ?>" autocomplete="off" class="form-control" value="<?php echo $orderItemData['total']; ?>"/>			  					
									</td>
									<td>

										<button class="btn btn-default removeProductRowBtn" type="button" id="removeProductRowBtn" onclick="removeProductRow(<?php echo $x; ?>)"><i class="glyphicon glyphicon-trash"></i></button>
									</td>
								</tr>
								<?php
								$arrayNumber++;
								$x++;
			  		} // /for
			  		?>
			  	</tbody>			  	
			  </table>

			  <div class="col-md-6">
			  	<div class="form-group">
			  		<label for="subTotal" class="col-sm-3 control-label">Цена без ДДС</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="subTotal" name="subTotal" disabled="true" value="<?php echo $data[4] ?>" />
			  			<input type="hidden" class="form-control" id="subTotalValue" name="subTotalValue" value="<?php echo $data[4] ?>" />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="vat" class="col-sm-3 control-label">ДДС 20%</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="vat" name="vat" disabled="true" value="<?php echo $data[5] ?>"  />
			  			<input type="hidden" class="form-control" id="vatValue" name="vatValue" value="<?php echo $data[5] ?>"  />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="totalAmount" class="col-sm-3 control-label">Цена с ДДС</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="totalAmount" name="totalAmount" disabled="true" value="<?php echo $data[6] ?>" />
			  			<input type="hidden" class="form-control" id="totalAmountValue" name="totalAmountValue" value="<?php echo $data[6] ?>"  />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="discount" class="col-sm-3 control-label">Отстъпка(Т.О)</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="discount" name="discount" onkeyup="discountFunc()" autocomplete="off" value="<?php echo $data[7] ?>" />
			  		</div>
			  	</div> <!--/form-group-->	
			  	<div class="form-group">
			  		<label for="grandTotal" class="col-sm-3 control-label">Обща цена</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="grandTotal" name="grandTotal" disabled="true" value="<?php echo $data[8] ?>"  />
			  			<input type="hidden" class="form-control" id="grandTotalValue" name="grandTotalValue" value="<?php echo $data[8] ?>"  />
			  		</div>
			  	</div> <!--/form-group-->			  		  
			  </div> <!--/col-md-6-->

			  <div class="col-md-6">
			  	<div class="form-group">
			  		<label for="paid" class="col-sm-3 control-label">Платено</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="paid" name="paid" autocomplete="off" onkeyup="paidAmount()" value="<?php echo $data[9] ?>"  />
			  		</div>
			  	</div> <!--/form-group-->			  
			  	<div class="form-group">
			  		<label for="due" class="col-sm-3 control-label">За плащане</label>
			  		<div class="col-sm-9">
			  			<input type="text" class="form-control" id="due" name="due" disabled="true" value="<?php echo $data[10] ?>"  />
			  			<input type="hidden" class="form-control" id="dueValue" name="dueValue" value="<?php echo $data[10] ?>"  />
			  		</div>
			  	</div> <!--/form-group-->		
			  	<div class="form-group">
			  		<label for="clientContact" class="col-sm-3 control-label">Тип плащане</label>
			  		<div class="col-sm-9">
			  			<select class="form-control" name="paymentType" id="paymentType" >
			  				<option value="">~~Избери~~</option>
			  				<option value="1" <?php if($data[11] == 1) {
			  					echo "selected";
			  				} ?> >Чек</option>
			  				<option value="2" <?php if($data[11] == 2) {
			  					echo "selected";
			  				} ?>  >Кеш</option>
			  				<option value="3" <?php if($data[11] == 3) {
			  					echo "selected";
			  				} ?> >Карта</option>
			  			</select>
			  		</div>
			  	</div> <!--/form-group-->							  
			  	<div class="form-group">
			  		<label for="clientContact" class="col-sm-3 control-label">Статус плащане</label>
			  		<div class="col-sm-9">
			  			<select class="form-control" name="paymentStatus" id="paymentStatus">
			  				<option value="">~~Избери~~</option>
			  				<option value="1" <?php if($data[12] == 1) {
			  					echo "selected";
			  				} ?>  >Плащане в пълен размер</option>
			  				<option value="2" <?php if($data[12] == 2) {
			  					echo "selected";
			  				} ?> >Аванс от 50%</option>
			  				<option value="3" <?php if($data[10] == 3) {
			  					echo "selected";
			  				} ?> >Безплатно</option>
			  			</select>
			  		</div>
			  	</div> <!--/form-group-->							  
			  </div> <!--/col-md-6-->


			  <div class="form-group editButtonFooter">
			  	<div class="col-sm-offset-2 col-sm-10">
			  		<button type="button" class="btn btn-default" onclick="addRow()" id="addRowBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-plus-sign"></i> Добави ред </button>

			  		<input type="hidden" name="orderId" id="orderId" value="<?php echo $_GET['i']; ?>" />

			  		<button type="submit" id="editOrderBtn" data-loading-text="Loading..." class="btn btn-success"><i class="glyphicon glyphicon-ok-sign"></i> Готово!</button>

			  	</div>
			  </div>
			</form>

			<?php
		} // /get order else  ?>


	</div> <!--/panel-->	
</div> <!--/panel-->	


<!-- edit order -->
<div class="modal fade" tabindex="-1" role="dialog" id="paymentOrderModal">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="glyphicon glyphicon-edit"></i> Промяна на плащане</h4>
			</div>      

			<div class="modal-body form-horizontal" style="max-height:500px; overflow:auto;" >

				<div class="paymentOrderMessages"></div>


				<div class="form-group">
					<label for="due" class="col-sm-3 control-label">За плащане</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="due" name="due" disabled="true" />					
					</div>
				</div> <!--/form-group-->		
				<div class="form-group">
					<label for="payAmount" class="col-sm-3 control-label">Платено</label>
					<div class="col-sm-9">
						<input type="text" class="form-control" id="payAmount" name="payAmount"/>					      
					</div>
				</div> <!--/form-group-->		
				<div class="form-group">
					<label for="clientContact" class="col-sm-3 control-label">Тип плащане</label>
					<div class="col-sm-9">
						<select class="form-control" name="paymentType" id="paymentType" >
							<option value="">~~Избери~~</option>
							<option value="1">Чек</option>
							<option value="2">Кеш</option>
							<option value="3">Карта</option>
						</select>
					</div>
				</div> <!--/form-group-->							  
				<div class="form-group">
					<label for="clientContact" class="col-sm-3 control-label">Статус плащане</label>
					<div class="col-sm-9">
						<select class="form-control" name="paymentStatus" id="paymentStatus">
							<option value="">~~Избери~~</option>
							<option value="1">Плащане в пълен размер</option>
							<option value="2">Аванс от 50%</option>
							<option value="3">Безплатно</option>
						</select>
					</div>
				</div> <!--/form-group-->							  				  

			</div> <!--/modal-body-->
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Затвори</button>
				<button type="button" class="btn btn-primary" id="updatePaymentOrderBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Готово!</button>	
			</div>           
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /edit order-->

<!-- remove order -->
<div class="modal fade" tabindex="-1" role="dialog" id="removeOrderModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title"><i class="glyphicon glyphicon-trash"></i> Изтрий поръчка</h4>
			</div>
			<div class="modal-body">

				<div class="removeOrderMessages"></div>

				<p>Сигурни ли сте, че искате да продължите?</p>
			</div>
			<div class="modal-footer removeProductFooter">
				<button type="button" class="btn btn-default" data-dismiss="modal"> <i class="glyphicon glyphicon-remove-sign"></i> Затвори</button>
				<button type="button" class="btn btn-primary" id="removeOrderBtn" data-loading-text="Loading..."> <i class="glyphicon glyphicon-ok-sign"></i> Да, сигурен съм!</button>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<!-- /remove order-->


<script src="custom/js/order.js"></script>

<?php require_once 'includes/footer.php'; ?>


