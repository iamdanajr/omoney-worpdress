<div class="container text-center m-auto p-5">
			
	<div class="row">
		<div class="col-12">
			<img class="img-fluid" style="width:300px" src="<?php echo OmoneyAdmin::get_logo(); ?>" alt="Logo" >
			<h3 class="font-weight-bold" style="color:#ff7900">Configuration</h3>
		</div>
	</div>

	<div class="row">
		<form action="<?php echo esc_url( OmoneyAdmin::get_page_url() ); ?>" method="POST">
			<div class="col-md-6 col-sm-12 offset-md-3 p-5 bg-dark text-white rounded text-left">

				<?php if(isset($_SESSION['omoney-config']) && !empty($_SESSION['omoney-config'])) { ?>
					<div class='alert alert-success alert-dismissible fade show' role='alert'><strong>Configuration changes successfully !</strong></div>
				<?php unset($_SESSION['omoney-config']); } ?>

				<div class="form-group mb-2">
					<input type="radio" class="form-check-input" name="omoney_environnemnt" value="1" <?php if($omoney_environnemnt == 1){ echo "checked"; } ?> >Developement
					<input type="radio" class="form-check-input" name="omoney_environnemnt" value="2" <?php if($omoney_environnemnt == 2){ echo "checked"; } ?>>Production
				</div>
			
				<div class="form-group mb-2">
					<label for="reference">OM - Reference</label>
					<input type="text" name="omoney_reference" id="omoney_reference" class="form-control" placeholder="Enter your orange money reference" value="<?php echo $omoney_reference; ?>">
				</div>

				<div class="form-group mb-2">
					<label for="reference">OM - Merchant</label>
					<input type="text" name="omoney_merchant" id="omoney_merchant" class="form-control" placeholder="Enter your orange money merchant number" value="<?php echo $omoney_merchant; ?>">
				</div>

				<div class="form-group mb-2">
					<label for="reference">OM - SECRET</label>
					<input type="text" name="omoney_secret" id="omoney_secret" class="form-control" placeholder="Enter your orange money secret key" value="<?php echo $omoney_secret; ?>">
				</div>

				<div class="form-group mb-2">
					<label for="reference">OM - Prefix</label>
					<input type="text" name="omoney_prefix" id="omoney_prefix" class="form-control" placeholder="Enter your orange money reference transaction prefix" value="<?php echo $omoney_prefix; ?>">
				</div>

				<?php wp_nonce_field(OmoneyAdmin::OMONEY_NONCE) ?>

				<input type="hidden" name="action" value="update-omoney-config">
				<hr>
				<button type="submit" id="button_check" class="btn btn-lg btn-block text-center" style="width:100%; background-color:#ff7900">Save changes</button>
			</div>
		</form>
	</div>

	<footer>
		<div class="row mt-5">
			<div class="col-12">
				<p class="font-weight-bold" style="color:#ff7900"> © Copyright <?php echo date("Y"); ?>.</p>
			</div>
		</div>
	</footer>
	
</div>