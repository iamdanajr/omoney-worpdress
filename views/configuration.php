<h2>
	Configuration - OM
</h2>

<form action="<?php echo esc_url( OmoneyAdmin::get_page_url() ); ?>" method="POST">

	<div class="form-group">
	  <label for="reference">OM - Reference</label>
	  <input type="text" name="omoney_reference" id="omoney_reference" class="form-control" placeholder="Enter your orange money reference" value="<?php echo $omoney_reference; ?>">
	</div>

	<div class="form-group">
	  <label for="reference">OM - Merchant</label>
	  <input type="text" name="omoney_merchant" id="omoney_merchant" class="form-control" placeholder="Enter your orange money merchant number" value="<?php echo $omoney_merchant; ?>">
	</div>

	<div class="form-group">
	  <label for="reference">OM - SECRET</label>
	  <input type="text" name="omoney_secret" id="omoney_secret" class="form-control" placeholder="Enter your orange money secret key" value="<?php echo $omoney_secret; ?>">
	</div>

	<div class="form-group">
	  <label for="reference">OM - Prefix</label>
	  <input type="text" name="omoney_prefix" id="omoney_prefix" class="form-control" placeholder="Enter your orange money reference transaction prefix" value="<?php echo $omoney_prefix; ?>">
	</div>


	<label for="environnement">OM - Environement</label>

	<div class="form-check">
	  <label class="form-check-label">
		<input type="radio" class="form-check-input" name="omoney_environnemnt" value="1" <?php if($omoney_environnemnt == 1){ echo "checked"; } ?> >
		Developement
	  </label>

	  <label class="form-check-label">
		<input type="radio" class="form-check-input" name="omoney_environnemnt" value="2" <?php if($omoney_environnemnt == 2){ echo "checked"; } ?>>
		Production
	  </label>
	</div>

	<?php wp_nonce_field(OmoneyAdmin::OMONEY_NONCE) ?>

	<input type="hidden" name="action" value="update-omoney-config">
	<input type="submit" name="submit" id="submit" class="btn btn-primary" value="<?php echo 'Save Changes'; ?>">

</form>